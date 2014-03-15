<?php

namespace AVCMS\Core\Database\QueryBuilder;

use AVCMS\Core\Database\Connection;
use AVCMS\Core\Database\Events\QueryBuilderModelJoinEvent;
use AVCMS\Core\Model\Model;
use Pixie\QueryBuilder\QueryBuilderHandler as PixieQueryBuilderHandler;

class QueryBuilderHandler extends PixieQueryBuilderHandler {
    /**
     * @var Model
     */
    protected $model;

    protected $entity;

    protected $sub_entities;

    /**
     * @var null|\Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $event_dispatcher = null;

    /**
     * @var Model[]
     */
    protected $model_joins = array();

    public function __construct(Connection $connection = null)
    {
        parent::__construct($connection);

        $this->event_dispatcher = $connection->getEventDispatcher();
    }

    /**
     * Get all rows
     *
     * @param string $class
     * @internal param null $query_name
     * @return mixed
     */
    public function get($class = 'stdClass')
    {
        if ($class == 'stdClass' && isset($this->entity)) {
            $class = $this->entity;
        }

        // If we have sub-entities, do the more complex method including joins
        if (isset($this->model)) {
            return $this->getEntity($class);
        }

        $this->fireEvents('before-select');
        $this->preparePdoStatement();

        $result = $this->pdoStatement->fetchAll(\PDO::FETCH_CLASS, $class);
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

        while ($row_array = $this->pdoStatement->fetch(\PDO::FETCH_ASSOC)) {
            $entity = $this->model->newEntity();

            if (!empty($this->model_joins)) {
                foreach ($this->model_joins as $join_name => $join_model) {

                    if (strpos($join_name, '__') !== false) {
                        $selected_entity = $this->findSubEntityFromColumn($entity, $join_name);
                        $join_name_explode = explode('__', $join_name);
                        $join_name = array_pop($join_name_explode);
                    }
                    else {
                        $selected_entity = $entity;
                    }
                    $sub_entity = $join_model->newEntity();

                    $selected_entity->addSubEntity($join_name, $sub_entity);
                }
            }

            foreach ($row_array as $column_name => $column_value) {

                $column_value_used = false;

                if (strpos($column_name, '__') !== false) {

                    $selected_entity = $this->findSubEntityFromColumn($entity, $column_name);
                    $sub_entities = explode('__', $column_name);
                    $column = array_pop($sub_entities);

                    if ($selected_entity != null) {
                        $setter_method_name = 'set'.$this->dashesToCamelCase($column, true);
                        if (method_exists($selected_entity, $setter_method_name)) {
                            $selected_entity->$setter_method_name($column_value);
                            $column_value_used = true;
                        }
                    }
                }
                else {
                    $setter_method_name = 'set'.$this->dashesToCamelCase($column_name, true);

                    if (method_exists($entity, $setter_method_name)) {
                        $entity->$setter_method_name($column_value);
                        $column_value_used = true;
                    }
                }

                if ($column_value_used == false) {
                    // TODO: LOG IN DEV MODE
                }
            }

            if (method_exists($entity, 'getId')) {
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

    protected function findSubEntityFromColumn($entity, $column_name, $ignore_last = true)
    {
        $sub_entities = explode('__', $column_name);

        if ($ignore_last) {
            array_pop($sub_entities);
        }

        $selected_entity = $entity;

        // Track down the right sub-entity recursively
        foreach ($sub_entities as $sub_entity_name) {
            if (isset($selected_entity->$sub_entity_name)) {
                $selected_entity = $selected_entity->$sub_entity_name;
            }
            else {
                // No matching sub-entity
                $selected_entity = null;
                break;
            }
        }

        return $selected_entity;
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
     * @param $fields
     * @param bool $join
     * @return $this
     */
    public function select($fields, $join = false)
    {
        if ($join == false) {
            $this->select_made = true;
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
        if (!isset($this->select_made)) {
            $fields[] = '*';
            $this->select($fields);
        }

        return parent::getQuery($type, $dataToBePassed);
    }

    /**
     * @param $entity \AVCMS\Core\Model\Entity
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
     * @param $entity_name
     * @param $join_id
     * @return $this
     */
    public function addSubEntity($entity_name, $join_id)
    {
        $this->sub_entities[] = array('class' => $entity_name, 'id' => $join_id);

        return $this;
    }

    /**
     * Do a one-to-one join based on data provided by a model
     *
     * @param Model $join_model The model used to create the join
     * @param array $columns The columns to join
     * @param string $type SQL Join type
     * @param null $join_to Select a sub-entity to join to
     * @param null|string $key Join 'on' operation key
     * @param null|string $operator Join 'on' operation operator
     * @param null $value Join 'on' operation value
     *
     * @throws \Exception
     *
     * @return QueryBuilderHandler
     */
    public function modelJoin(Model $join_model, array $columns = array(), $type = 'left', $join_to = null, $key = null, $operator = '=', $value = null)
    {
        if ($this->event_dispatcher) {
            $event = new QueryBuilderModelJoinEvent($join_model, $columns, $type);
            $this->event_dispatcher->dispatch('query_builder.model_join', $event);
            $columns = $event->getColumns();
            $type = $event->getType();
        }

        if ($join_to == null) {
            $this_table = $this->model->getTable();
        }
        else {
            if (!isset($this->model_joins[$join_to])) {
                throw new \Exception('Cannot join model '.get_class($join_model).' to '.$join_to.' because '.$join_to.' has not been joined to this query yet');
            }

            $this_table = $this->model_joins[$join_to]->getTable();
        }

        $join_singular = $join_model->getSingular();

        if ($join_to) {
            $join_singular = $join_to.'__'.$join_singular;
        }

        $join_table = $join_model->getTable();
        $join_column = $join_model->getJoinColumn($this_table);

        $columns_updated = array();

        foreach ($columns as $column) {
            $columns_updated[] = "{$join_table}.{$column}` as `{$join_singular}__{$column}"; // @todo do this better?
        }

        $this->select($columns_updated, true);

        if ($key == null && $value == null) {
            $key = $join_table.'.id';
            $value = $this_table.'.'.$join_column;
        }

        $this->join($join_table, $key, $operator, $value, $type);

        $this->addSubEntity($join_model->getEntity(), $join_singular);

        $this->model_joins[$join_singular] = $join_model;

        /*
        $a = $this->getQuery();
        echo $a->getRawSql();
        */
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
     * @param $data array|\AVCMS\Core\Model\Entity
     */
    public function update($data)
    {
        // Get data from entity
        if (is_a($data, 'AVCMS\Core\Model\Entity')) {
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
        // Get data from entity
        if (is_a($data, 'AVCMS\Core\Model\Entity')) {
            $entity = $data;
            $data = $entity->toArray();
        }

        $id = parent::insert($data);

        // Set insert ID on entity
        if (isset($entity) && method_exists($entity, 'setId')) {
            $entity->setId($id);
        }

        return $id;
    }

    /**
     * Convert something_like_this to somethingLikeThis
     *
     * @param $string
     * @param bool $capitalize_first_character
     * @return mixed
     */
    protected function dashesToCamelCase($string, $capitalize_first_character = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalize_first_character) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
} 