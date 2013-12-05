<?php

namespace AVCMS\Model;

use Pixie\QueryBuilder\QueryBuilderHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Model
 * @package AVCMS\Model
 */
class Model {

    /**
     * @var $query_builder QueryBuilderHandler
     */
    protected $query_builder;

    /**
     * @var $table string
     */
    protected $table;

    /**
     * @var Entity
     */
    protected $entity = 'stdClass';

    /**
     * @var integer
     */
    protected $last_insert_id;

    /**
     *
     */
    public  function __construct($query_builder, EventDispatcher $event_dispatcher)
    {
        $this->query_builder = $query_builder;
    }

    public function select($columns = '*')
    {
        $columns2[] = $this->table.'.'.$columns; // @todo sort this out
        if (isset($this->joins)) {
            foreach ($this->joins as $join) {
                foreach ($join['columns'] as $column)
                $columns2[] = $column;
            }
        }

        $query = $this->query()->select($columns2);

        //echo $query->getQuery()->getSql();
        return $query;
    }

    /**
     * @return static|QueryBuilderHandler
     */
    public function query()
    {
        $query = $this->query_builder->table($this->table)->entity($this->entity);

        if (isset($this->joins)) {
            foreach ($this->joins as $join) {
                $entity_name = $join['model']->getEntity();
                $sub_entities[] = array('class' => $entity_name, 'id' => $join['id']);
                $table = $join['model']->getTable();

                $query->join($table, $table.'.id', '=', $this->table.'.category_id');
            }
            if (isset($sub_entities)) {
                $query->sub_entities($sub_entities);
            }
        }

        return $query;
    }

    public function find($id)
    {
        return $this->select()->where($this->table.'.id', $id);
    }

    public function save(Entity $entity)
    {
        if (isset($entity->id)) {
            $this->update($entity);
        }
        else {
            $this->insert($entity);
        }
    }

    public function insert(Entity $entity)
    {
        if ($entity->hasField('date_added')) {
            $date = new \DateTime();
            $entity->date_added = $date->getTimestamp();
        }

        $insert_id = $this->query()->insert($entity->getData());

        if ($insert_id) {
            $this->last_insert_id = $insert_id;
        }
    }

    public function update(Entity $entity)
    {
        $this->query()->where('id', $entity->id)->update($entity->getData());
    }

    public function setJoin(Model $join_model, $columns = array())
    {
        $sub_entity_id = lcfirst(substr($join_model->getEntity(), strrpos( $join_model->getEntity(), "\\") + 1));
        foreach ($columns as $column) {
            $columns_updated[] = "categories.{$column}` as `{$sub_entity_id}.{$column}"; // @todo do this better?
        }
        $this->joins[] = array('model'=>$join_model, 'id' => $sub_entity_id, 'columns'=>$columns_updated);
    }

    public function getInsertId()
    {
        return $this->last_insert_id;
    }

    public function newEntity()
    {
        return new $this->entity;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}