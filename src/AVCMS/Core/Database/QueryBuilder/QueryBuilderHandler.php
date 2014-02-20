<?php

namespace AVCMS\Core\Database\QueryBuilder;

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
     * @var Model[]
     */
    protected $model_joins = array();

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
                    $sub_entity = $join_model->newEntity();
                    $entity->addSubEntity($join_name, new $sub_entity);
                }
            }

            foreach ($row_array as $column_name => $column_value) {

                $column_value_used = false;

                if (strpos($column_name, '__') !== false) {
                    $sub_entity_name = strstr($column_name, '__', true);
                    $column = str_replace($sub_entity_name.'__', '', $column_name);

                    if (isset($entity->$sub_entity_name)) {
                        $sub_entity = $entity->$sub_entity_name;

                        $setter_method_name = 'set'.$this->dashesToCamelCase($column, true);
                        if (method_exists($sub_entity, $setter_method_name)) {
                            $sub_entity->$setter_method_name($column_value);
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

            $result[] = $entity;
        }

        $this->pdoStatement = null;
        $this->fireEvents('after-select', $result);

        return $result;
    }

    /**
     * Get first row
     *
     * @param string $class
     * @return mixed
     */
    public function first($class = 'stdClass')
    {
        $this->limit(1);

        if ($class == 'stdClass' && isset($this->entity)) {
            $class = $this->entity;
        }

        if (isset($this->sub_entities)) {
           $result = $this->getEntity($class);
        }
        else {
            $result = $this->get($class);
        }

        return empty($result) ? null : $result[0];
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
     * @param array $entities
     * @return $this
     */
    public function sub_entities(array $entities)
    {
        $this->sub_entities = $entities;

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
     * @param Model $join_model
     * @param array $columns
     * @return $this
     */
    public function modelJoin(Model $join_model, array $columns = array())
    {
        $this_table = $this->model->getTable();

        $join_singular = $join_model->getSingular();
        $join_table = $join_model->getTable();
        $join_column = $join_model->getJoinColumn($this_table);

        $columns_updated = array();

        foreach ($columns as $column) {
            $columns_updated[] = "{$join_table}.{$column}` as `{$join_singular}__{$column}"; // @todo do this better?
        }

        $this->select($columns_updated, true);
        $this->join($join_table, $join_table.'.id', '=', $this_table.'.'.$join_column);

        $this->addSubEntity($join_model->getEntity(), $join_singular);

        $this->model_joins[$join_singular] = $join_model;

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