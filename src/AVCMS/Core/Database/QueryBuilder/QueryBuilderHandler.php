<?php

namespace AVCMS\Core\Database\QueryBuilder;

use AVCMS\Core\Database\Connection;
use AVCMS\Core\Database\Events\QueryBuilderModelJoinEvent;
use AV\Model\Model;
use AVCMS\Core\Taxonomy\Model\TaxonomyModel;
use Pixie\QueryBuilder\QueryBuilderHandler as PixieQueryBuilderHandler;

class QueryBuilderHandler extends PixieQueryBuilderHandler {
    /**
     * @var Model
     */
    protected $model;

    protected $entity;

    protected $subEntities;

    /**
     * @var null|\Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher = null;

    /**
     * @var Model[]
     */
    protected $modelJoins = array();

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
        if ($class == null && isset($this->entity) && $this->entity !== null) {
            $class = $this->entity;
        }

        if (isset($this->model) && $class != 'stdClass' && $class != null) {
            return $this->getEntity($class);
        }

        if ($class == null && $fetchType === \PDO::FETCH_CLASS) {
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

                $columnValueUsed = false;

                if (strpos($columnName, '__') !== false) {

                    $selectedEntity = $this->findSubEntityFromColumn($entity, $columnName);
                    $subEntities = explode('__', $columnName);
                    $column = array_pop($subEntities);

                    if ($selectedEntity != null) {
                        $setterMethodName = 'set'.str_replace('_', '', $column);
                        if (method_exists($selectedEntity, $setterMethodName)) {
                            $selectedEntity->$setterMethodName($columnValue);
                            $columnValueUsed = true;
                        }
                    }
                }
                else {
                    $setterMethodName = 'set'.str_replace('_', '', $columnName);

                    if (method_exists($entity, $setterMethodName)) {
                        $entity->$setterMethodName($columnValue);
                        $columnValueUsed = true;
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
     * @param string $class
     * @return mixed
     */
    public function first($class = 'stdClass')
    {
        $query = clone($this);

        $query->limit(1);

        if ($class == 'stdClass' && isset($this->entity)) {
            $class = $this->entity;
        }

        if (isset($this->model)) {
           $result = $query->getEntity($class);
        }
        else {
            $result = $query->get($class);
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
     * @return $this
     */
    public function select($fields, $join = false)
    {
        if ($join == false) {
            $this->selectMade = true;
        }

        // If this is a model-based select, we automatically add the table to the fields
        if (isset($this->model) && $join === false) {
            $fields_tabled = array();
            foreach ($fields as $field) {
                if (!strpos($field, '.')) {
                    $field = $this->model->getTable().'.'.$field;
                }
                $fields_tabled[] = $field;
            }
            $fields = $fields_tabled;
        }

        $fields = $this->addTablePrefix($fields);
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
     * @internal param \AVCMS\Core\Database\QueryBuilder\Entity $entity
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
     * Do a one-to-one join based on data provided by a model
     *
     * @param Model $joinModel The model used to create the join
     * @param array $columns The columns to join
     * @param string $type SQL Join type
     * @param null $joinTo Select a sub-entity to join to
     * @param null|string $key Join 'on' operation key
     * @param null|string $operator Join 'on' operation operator
     * @param null $value Join 'on' operation value
     *
     * @throws \Exception
     *
     * @return QueryBuilderHandler
     */
    public function modelJoin(Model $joinModel, array $columns, $type = 'left', $joinTo = null, $key = null, $operator = '=', $value = null)
    {
        if ($this->eventDispatcher) {
            $event = new QueryBuilderModelJoinEvent($joinModel, $columns, $type);
            $this->eventDispatcher->dispatch('query_builder.model_join', $event);
            $columns = $event->getColumns();
            $type = $event->getType();
        }

        if ($joinTo == null) {
            $thisTable = $this->model->getTable();
        }
        else {
            if (!isset($this->modelJoins[$joinTo])) {
                throw new \Exception('Cannot join model '.get_class($joinModel).' to '.$joinTo.' because '.$joinTo.' has not been joined to this query yet');
            }

            $thisTable = $this->modelJoins[$joinTo]->getTable();
        }

        $joinSingular = $joinModel->getSingular();

        if ($joinTo) {
            $joinSingular = $joinTo.'__'.$joinSingular;
        }

        $joinTable = $joinModel->getTable();
        $joinColumn = $joinModel->getJoinColumn($thisTable);

        $columnsUpdated = array();

        foreach ($columns as $column) {
            $columnsUpdated[] = "{$joinTable}.{$column}` as `{$joinSingular}__{$column}";
        }

        $this->select($columnsUpdated, true);

        if ($key == null && $value == null) {
            $key = $joinTable.'.id';
            $value = $thisTable.'.'.$joinColumn;
        }

        $this->join($joinTable, $key, $operator, $value, $type);

        $this->addSubEntity($joinModel->getEntity(), $joinSingular);

        $this->modelJoins[$joinSingular] = $joinModel;

        /*
        $a = $this->getQuery();
        echo $a->getRawSql();
        */
        return $this;
    }

    /**
     * @param $taxonomyModel
     * @param $equals
     * @return $this
     */
    public function taxonomy(TaxonomyModel $taxonomyModel, array $equals)
    {
        $taxTable = $taxonomyModel->getTable();
        $taxonomyFieldId = 'content_id';

        $this->join($taxTable, $taxonomyFieldId, '=', $this->model->getTable().'.id', 'left')
             ->where('content_type', $this->model->getSingular())
             ->whereIn($taxTable.'.id', $equals);

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
     * Update the database using an array or AVCMS entity
     *
     * @param $data array|\AV\Model\Entity
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
     * Insert a row into the database using an array or AVCMS entity
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


    public function paginated($page, $resultsPerPage) {
        $offset = $resultsPerPage * ($page - 1);

        return $this->limit($resultsPerPage)->offset($offset);
    }

    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }
} 