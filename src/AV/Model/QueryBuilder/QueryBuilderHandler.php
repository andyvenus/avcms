<?php

namespace AV\Model\QueryBuilder;

use AV\Model\Connection;
use AV\Model\Event\QueryBuilderModelJoinEvent;
use AV\Model\Model;
use Pixie\QueryBuilder\QueryBuilderHandler as PixieQueryBuilderHandler;
use Pixie\QueryBuilder\Raw;

class QueryBuilderHandler extends PixieQueryBuilderHandler {
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var \AV\Model\Entity
     */
    protected $entity;

    /**
     * @var \AV\Model\Entity[]
     */
    protected $subEntities;

    /**
     * @var null|\Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher = null;

    /**
     * @var Model[]
     */
    protected $modelJoins = array();

    /**
     * @var bool
     */
    protected $selectMade;

    public function __construct(Connection $connection = null)
    {
        parent::__construct($connection);

        $this->eventDispatcher = $connection->getEventDispatcher();
    }

    /**
     * Get all rows
     *
     * @param string $class
     * @param int $fetchType
     * @return mixed
     */
    public function get($class = null, $fetchType = \PDO::FETCH_CLASS)
    {
        if ($class === null && isset($this->entity) && $this->entity !== null) {
            $class = $this->entity;
        }

        if (isset($this->model) && $class != 'stdClass' && $class !== null && $fetchType === \PDO::FETCH_CLASS) {
            return $this->getEntity($class);
        }

        if ($class === null && $fetchType === \PDO::FETCH_CLASS) {
            $class = 'stdClass';
        }

        $this->fireEvents('before-select');
        $this->preparePdoStatement();

        if ($fetchType === \PDO::FETCH_CLASS) {
            $result = $this->pdoStatement->fetchAll($fetchType, $class);
        }
        else {
            $result = $this->pdoStatement->fetchAll($fetchType);
        }
        $this->pdoStatement = null;
        $this->fireEvents('after-select', $result);
        return $result;
    }

    /**
     * Get all rows with join as sub-entities
     *
     * @return mixed
     */
    protected function getEntity()
    {
        $this->fireEvents('before-select');

        $this->preparePdoStatement();

        $result = array();

        while ($rowArray = $this->pdoStatement->fetch(\PDO::FETCH_ASSOC)) {
            $entity = $this->model->newEntity();

            if (!empty($this->modelJoins)) {
                foreach ($this->modelJoins as $joinName => $joinModel) {

                    if (strpos($joinName, '__') !== false) {
                        $selectedEntity = $this->findSubEntityFromColumn($entity, $joinName);
                        $joinNameExplode = explode('__', $joinName);
                        $joinName = array_pop($joinNameExplode);
                    }
                    else {
                        $selectedEntity = $entity;
                    }
                    $subEntity = $joinModel->newEntity();

                    $selectedEntity->addSubEntity($joinName, $subEntity);
                }
            }

            foreach ($rowArray as $columnName => $columnValue) {
                if (strpos($columnName, '__') !== false) {

                    $selectedEntity = $this->findSubEntityFromColumn($entity, $columnName);
                    $subEntities = explode('__', $columnName);
                    $column = array_pop($subEntities);

                    if ($selectedEntity !== null) {
                        $setterMethodName = 'set'.str_replace('_', '', $column);
                        if (method_exists($selectedEntity, $setterMethodName)) {
                            $selectedEntity->$setterMethodName($columnValue);
                        }
                    }
                }
                else {
                    $setterMethodName = 'set'.str_replace('_', '', $columnName);

                    if (method_exists($entity, $setterMethodName)) {
                        $entity->$setterMethodName($columnValue);
                    }
                }
            }

            if (method_exists($entity, 'getId') && $entity->getID() !== null) {
                $result[ $entity->getId() ] = $entity;
            }
            else {
                $result[] = $entity;
            }
        }

        $this->pdoStatement = null;
        $this->fireEvents('after-select', $result);

        return $result;
    }

    protected function findSubEntityFromColumn($entity, $columnName, $ignoreLast = true)
    {
        $subEntities = explode('__', $columnName);

        if ($ignoreLast) {
            array_pop($subEntities);
        }

        $selectedEntity = $entity;

        // Track down the right sub-entity recursively
        foreach ($subEntities as $subEntityName) {
            if (isset($selectedEntity->$subEntityName)) {
                $selectedEntity = $selectedEntity->$subEntityName;
            } else {
                // No matching sub-entity
                $selectedEntity = null;
                break;
            }
        }

        return $selectedEntity;
    }

    /**
     * Get first row
     *
     * @param int $fetchType
     * @return mixed
     */
    public function first($fetchType = \PDO::FETCH_CLASS)
    {
        $query = clone($this);

        $query->limit(1);

        if (isset($this->model) && isset($this->entity) && $this->entity !== null && $fetchType == \PDO::FETCH_CLASS) {
           $result = $query->getEntity($this->entity);
        }
        else {
            $result = $query->get(null, $fetchType);
        }

        return empty($result) ? null : reset($result);
    }

    /**
     * Get an array of column values
     *
     * @param $column
     * @return mixed
     */
    public function getColumn($column)
    {
        $this->preparePdoStatement();

        $result = $this->pdoStatement->fetchAll(\PDO::FETCH_COLUMN, $column);
        $this->pdoStatement = null;
        return $result;
    }

    /**
     * @param $fields
     * @param bool $join
     * @param bool $prefix
     * @return $this
     */
    public function select($fields, $join = false, $prefix = true)
    {
        $isCount = (strpos($fields[0], '(*) as field') !== false);

        if ($join === false && $isCount === false) {
            $this->selectMade = true;
        }

        // If this is a model-based select, we automatically add the table to the fields
        if (isset($this->model) && $join === false) {
            $fieldsTabled = array();
            foreach ($fields as $field) {
                if (!strpos($field, '.') && !$field instanceof Raw) {
                    $field = $this->model->getTable().'.'.$field;
                }
                $fieldsTabled[] = $field;
            }
            $fields = $fieldsTabled;
        }

        if ($prefix) {
            $fields = $this->addTablePrefix($fields);
        }
        $this->addStatement('selects', $fields);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery($type = 'select', $dataToBePassed = array())
    {
        // Automatically generating the "SELECT *" here instead of deeper down
        if (!isset($this->selectMade)) {
            $fields[] = '*';
            $this->select($fields);
        }

        return parent::getQuery($type, $dataToBePassed);
    }

    /**
     * @param $entity \AV\Model\Entity
     * @return $this
     */
    public function entity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param $model
     * @internal param \AV\Model\QueryBuilder\Entity $entity
     * @return $this
     */
    public function model($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Add a sub-entity that allows extension of the main entity
     *
     * @param $entityName
     * @param $joinId
     * @return $this
     */
    public function addSubEntity($entityName, $joinId)
    {
        $this->subEntities[] = array('class' => $entityName, 'id' => $joinId);

        return $this;
    }

    /**
     * Do a one-to-one join of one Model onto another
     *
     * @param Model $joinModel The model used to create the join
     * @param array $columns The columns to join
     * @param string $type SQL Join type
     * @param null $joinTo Select a sub-entity to join to
     * @param null|string $key Join 'on' operation key
     * @param null|string $operator Join 'on' operation operator
     * @param null $value Join 'on' operation value
     *
     * @param null $joinSingular
     * @throws \Exception
     * @return QueryBuilderHandler
     */
    public function modelJoin(Model $joinModel, array $columns, $type = 'left', $joinTo = null, $key = null, $operator = '=', $value = null, $joinSingular = null)
    {
        if ($this->eventDispatcher) {
            $event = new QueryBuilderModelJoinEvent($joinModel, $columns, $type);
            $this->eventDispatcher->dispatch('query_builder.model_join', $event);
            $columns = $event->getColumns();
            $type = $event->getType();
        }

        if ($joinTo === null) {
            $thisTable = $this->model->getTable();
        }
        else {
            if (!isset($this->modelJoins[$joinTo])) {
                throw new \Exception('Cannot join model '.get_class($joinModel).' to '.$joinTo.' because '.$joinTo.' has not been joined to this query yet');
            }

            $thisTable = $this->modelJoins[$joinTo]->getTable();
        }

        $alias = $joinSingular;
        $joinTable = $alias;
        $originalTable = $joinModel->getTable();

        if (!$joinSingular) {
            $joinSingular = $joinModel->getSingular();
            $joinTable = $originalTable;
        }

        if ($joinTo) {
            $joinSingular = $joinTo.'__'.$joinSingular;
        }


        $joinColumn = $joinModel->getJoinColumn($thisTable);

        $columnsUpdated = array();

        foreach ($columns as $column) {
            $columnsUpdated[] = "{$joinTable}.{$column}` as `{$joinSingular}__{$column}";
        }

        $prefix = true;
        if ($alias !== null) {
            $prefix = false;
        }

        $this->select($columnsUpdated, true, $prefix);

        if ($key === null && $value === null) {
            $key = $joinTable.'.id';
            $value = $thisTable.'.'.$joinColumn;
        }

        $this->join($originalTable, $key, $operator, $value, $type, $alias);

        $this->addSubEntity($joinModel->getEntity(), $joinSingular);

        $this->modelJoins[$joinSingular] = $joinModel;

        return $this;
    }

    /**
     * @param $table
     * @param $key
     * @param null $operator
     * @param null $value
     * @param string $type
     * @param null $alias
     * @return $this
     */
    public function join($table, $key, $operator = null, $value = null, $type = 'inner', $alias = null)
    {
        if (!$key instanceof \Closure) {
            $key = function(JoinBuilder $joinBuilder) use ($key, $operator, $value, $alias) {
                $joinBuilder->on($key, $operator, $value, $alias);
            };
        }

        // Build a new JoinBuilder class, keep it by reference so any changes made
        // in the closure should reflect here
        $joinBuilder = $this->container->build('\\AV\\Model\\QueryBuilder\\JoinBuilder', array($this->connection));

        // Call the closure with our new joinBuilder object
        $key($joinBuilder);
        $table = $this->addTablePrefix($table, false);

        if ($alias !== null) {
            $table .= '` as `'.$alias;
        }
        // Get the criteria only query from the joinBuilder object
        $this->statements['joins'][] = compact('type', 'table', 'joinBuilder');

        return $this;
    }

    /**
     * Start a query based on data provided by a Model class
     *
     * @param Model $model
     * @return $this
     */
    public function modelQuery(Model $model)
    {
        return $this->table($model->getTable())->entity($model->getEntity())->model($model);
    }

    /**
     * Update the database using an array or Entity
     *
     * @param $data array|\AV\Model\Entity
     * @return void
     */
    public function update($data)
    {
        // Get data from entity
        if (is_a($data, 'AV\Model\Entity')) {
            $data = $data->toArray();
        }

        parent::update($data);
    }

    /**
     * Insert a row into the database using an array or Entity
     *
     * @param $data
     * @return array|string
     */
    public function insert($data)
    {
        $entities = [];
        // Get data from entity
        if (is_a($data, 'AV\Model\Entity')) {
            $entities[] = $data;
            $data = $data->toArray();
        }

        // Possible array of data
        if (is_array($data) && isset($data[0])) {
            foreach ($data as $entity) {
                if (is_a($entity, 'AV\Model\Entity')) {
                    $entities[] = $entity;
                    $entityData[] = $entity->toArray();
                }
            }

            if (isset($entityData)) {
                $data = $entityData;
            }
        }

        $id = parent::insert($data);

        foreach ($entities as $entity) {
            if (method_exists($entity, 'setId') && (!method_exists($entity, 'getId') || $entity->getId() === null)) {
                $entity->setId($id);
            }
        }

        return $id;
    }

    /**
     * Get count of rows
     *
     * @return int
     */
    public function count()
    {
        $selectMade = false;
        if (isset($this->selectMade)) {
            $selectMade = true;
        }

        // Get the current statements
        $originalStatements = $this->statements;

        unset($this->statements['limit']);
        unset($this->statements['offset']);

        $count = $this->aggregate('count');
        $this->statements = $originalStatements;

        if ($selectMade == false) {
            unset($this->selectMade);
        }

        return $count;
    }

    /**
     * Updated to use stdClass
     *
     * @param $type
     *
     * @return int
     */
    protected function aggregate($type)
    {
        // Get the current selects
        $mainSelects = isset($this->statements['selects']) ? $this->statements['selects'] : null;
        // Replace select with a scalar value like `count`
        $this->statements['selects'] = array($this->raw($type . '(*) as field'));
        $row = $this->get('stdClass');

        // Set the select as it was
        if ($mainSelects) {
            $this->statements['selects'] = $mainSelects;
        } else {
            unset($this->statements['selects']);
        }

        return isset($row[0]->field) ? (int) $row[0]->field : 0;
    }

    /**
     * Set a limit and offset based on a page number and the number of results per page
     *
     * @param $page
     * @param $resultsPerPage
     * @return $this
     */
    public function paginated($page, $resultsPerPage) {
        $offset = $resultsPerPage * ($page - 1);

        return $this->limit($resultsPerPage)->offset($offset);
    }

    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }
}
