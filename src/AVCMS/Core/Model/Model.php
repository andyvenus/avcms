<?php

namespace AVCMS\Core\Model;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Model
 * @package AVCMS\Core\Model
 *
 * TODO: Make singular, entity and table name accessed through methods defined in an interface
 */
abstract class Model implements ModelInterface {

    /**
     * @var $query_builder QueryBuilderHandler
     */
    protected $query_builder;

    /**
     * @var integer
     */
    protected $last_insert_id;

    /**
     * @var array Joined on models
     */
    protected $joins;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $event_dispatcher;

    /**
     * @var array
     */
    protected $sub_entities = array();

    /**
     *
     */
    public  function __construct(QueryBuilderHandler $query_builder, EventDispatcher $event_dispatcher)
    {
        $this->query_builder = $query_builder;
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * @return QueryBuilderHandler
     */
    public function query()
    {
        $query = $this->query_builder->modelQuery($this);

        return $query;
    }

    /**
     * @param $id
     * @return QueryBuilderHandler
     */
    public function find($id)
    {
        return $this->query()->where($this->getTable().'.id', $id);
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
            $entity->setDateAdded($date->getTimestamp());
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

    public function newEntity()
    {
        $entity_name = $this->getEntity();
        $entity = new $entity_name();

        foreach ($this->sub_entities as $sub_entity_name => $sub_entity) {
            $sub_entity_instance = new $sub_entity['class']();

            $entity->addSubEntity($sub_entity_name, $sub_entity_instance, true);
        }
        return $entity;
    }

    public function deleteById($id)
    {
        $this->query()->where('id', $id)->delete();
    }

    public function getInsertId()
    {
        return $this->last_insert_id;
    }

    public function getJoinColumn($table) // todo: support alternate column names
    {
        return $this->getSingular().'_id';
    }

    public function addOverflowEntity($overflow_name, $class_name)
    {
        $this->sub_entities[$overflow_name] = array('class' => $class_name, 'type' => 'overflow');
    }
}