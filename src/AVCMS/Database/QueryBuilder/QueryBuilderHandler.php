<?php

namespace AVCMS\Database\QueryBuilder;

use AVCMS\Model\Model;
use Pixie\QueryBuilder\QueryBuilderHandler as PixieQueryBuilderHandler;

class QueryBuilderHandler extends PixieQueryBuilderHandler {
    /**
     * @var Model
     */
    protected $model;

    protected $entity;

    protected $sub_entities;

    protected $joins;

    /**
     * Get all rows
     *
     * @param string $class
     * @return mixed
     */
    public function get($class = 'stdClass')
    {
        if ($class == 'stdClass' && isset($this->entity)) {
            $class = $this->entity;
        }

        // If we have sub-entities, do the more complex method including joins
        if (isset($this->sub_entities)) {
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
     * @param string $class
     * @return mixed
     */
    protected function getEntity($class = 'stdClass')
    {
        $this->fireEvents('before-select');

        $this->preparePdoStatement();

        while ($row_array = $this->pdoStatement->fetch(\PDO::FETCH_ASSOC)) {
            $entity = new $class;

            if (isset($this->sub_entities)) {
                foreach ($this->sub_entities as $sub_entity) {
                    $entity->addSubEntity($sub_entity['id'], new $sub_entity['class']);
                }
            }

            foreach ($row_array as $column_name => $column_value) {
                if (isset($this->sub_entities) && strpos($column_name, '.') !== false) {
                    $sub_entity_name = strstr($column_name, '.', true);
                    $column = str_replace($sub_entity_name.'.', '', $column_name);

                    $sub_entity = $entity->$sub_entity_name;
                    $sub_entity->{$column} = $column_value;
                }
                else {
                    $entity->{$column_name} = $column_value;
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
     * @param $entity \AVCMS\Model\Entity
     * @return $this
     */
    public function entity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param $model
     * @internal param \AVCMS\Database\QueryBuilder\Entity $entity
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

    public function addSubEntity($entity_name, $join_id)
    {
        $this->sub_entities[] = array('class' => $entity_name, 'id' => $join_id);

        return $this;
    }

    public function modelJoin(Model $join_model, array $columns = array())
    {
        $this_table = $this->model->getTable();

        $join_singular = $join_model->getSingular();
        $join_table = $join_model->getTable();
        $join_column = $join_model->getJoinColumn($this_table);

        $columns_updated = array();

        foreach ($columns as $column) {
            $columns_updated[] = "{$join_table}.{$column}` as `{$join_singular}.{$column}"; // @todo do this better?
        }

        $this->select($columns_updated, true);
        $this->join($join_table, $join_table.'.id', '=', $this_table.'.'.$join_column);

        $this->addSubEntity($join_model->getEntity(), $join_singular);

        return $this;
    }
} 