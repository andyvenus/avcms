<?php

namespace AVCMS\Core\Model;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Model
 * @package AVCMS\Core\Model
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
     * @var \AVCMS\Core\Model\Entity
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
    public  function __construct(QueryBuilderHandler $query_builder)
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
     * @return QueryBuilderHandler
     */
    public function query()
    {
        $query = $this->query_builder->table($this->table)->entity($this->entity)->model($this);

        return $query;
    }

    /**
     * @param $id
     * @return QueryBuilderHandler
     */
    public function find($id)
    {
        return $this->query()->where($this->table.'.id', $id);
    }

    /**
     * @param $id
     * @return Entity|mixed
     */
    public function getOne($id)
    {
        $query = $this->find($id);

        return $query->first();
    }

    public function save(Entity $entity)
    {
        if ($entity->getId()) {
            $this->update($entity);
        }
        else {
            $this->insert($entity);
        }
    }

    public function insert(Entity $entity)
    {
        if (method_exists($entity, 'setDateAdded') && !$entity->getDateAdded()) {
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
        $this->query()->where('id', $entity->getID())->update($entity->getData());
    }

    public function delete(Entity $entity)
    {
        if ($entity->getId()) {
            $this->deleteById($entity->getId());
        }
        else {
            throw new \Exception("The entity passed to delete() does not have an ID set");
        }
    }

    public function deleteById($id)
    {
        $this->query()->where('id', $id)->delete();
    }

    public function getInsertId()
    {
        return $this->last_insert_id;
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