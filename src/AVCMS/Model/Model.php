<?php

namespace AVCMS\Model;

use AVCMS\Database\QueryBuilder\QueryBuilderHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Model
 * @package AVCMS\Model
 */
abstract class Model {

    /**
     * @var $query_builder QueryBuilderHandler
     */
    protected $query_builder;

    /**
     * @var $table string
     */
    protected $table;

    /**
     * @var $singular string The singular for what the database table contains. For example, if the table is "articles" the singular would be "article"
     */
    protected $singular;

    /**
     * @var \AVCMS\Model\Entity
     */
    protected $entity = 'stdClass';

    /**
     * @var integer
     */
    protected $last_insert_id;

    /**
     * @var array Joined on models
     */
    protected $joins;

    /**
     *
     */
    public  function __construct($query_builder, EventDispatcher $event_dispatcher)
    {
        $this->query_builder = $query_builder;

        if (!isset($this->table)) {
            throw new \Exception("Model '".get_class($this)."' does not have a database table defined");
        }
        elseif (!isset($this->singular)) {
            throw new \Exception("Model '".get_class($this)."' does not have a a 'singular' definition");
        }
    }

    /**
     * @return static|QueryBuilderHandler
     */
    public function query()
    {
        $query = $this->query_builder->table($this->table)->entity($this->entity)->model($this);

        return $query;
    }

    /**
     * @param $id
     * @return static|QueryBuilderHandler
     */
    public function find($id)
    {
        return $this->query()->where($this->table.'.id', $id);
    }

    public function getOne($id)
    {
        $query = $this->query()
            ->where('id', $id);

        return $query->first();
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

    public function getSingular()
    {
        return $this->singular;
    }

    public function getJoinColumn($table)
    {
        return $this->singular.'_id';
    }
}