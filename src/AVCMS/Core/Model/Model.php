<?php

namespace AVCMS\Core\Model;

use AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler;
use AVCMS\Core\Model\Event\ModelInsertEvent;
use AVCMS\Core\Model\Event\ModelUpdateEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class Model
 * @package AVCMS\Core\Model
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
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $event_dispatcher;

    /**
     * @var array Sub entities that are auto-added to the base entity when newEntity() is called
     */
    protected $sub_entities = array();

    /**
     * @var string
     */
    protected $number_identifier_column = 'id';

    /**
     * @var string
     */
    protected $text_identifier_column = 'slug';

    /**
     * @param QueryBuilderHandler $query_builder
     * @param EventDispatcher $event_dispatcher
     */
    public  function __construct(QueryBuilderHandler $query_builder, EventDispatcher $event_dispatcher)
    {
        $this->query_builder = $query_builder;
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * Create a query using this model's information and return it
     *
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
        if (is_numeric($id)) {
            $column = $this->number_identifier_column;
        }
        else {
            $column = $this->text_identifier_column;
        }

        return $this->query()->where($this->getTable().'.'.$column, $id);
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

    /**
     * Get one result by ID or get a new entity if $id is 0 (or null etc)
     *
     * @param $id
     * @return Entity|mixed
     */
    public function getOneOrNew($id)
    {
        if (!$id) {
            return $this->newEntity();
        }
        else {
            return $this->getOne($id);
        }
    }

    /**
     * Save the data stored in an entity to the database
     *  * Insert new row if no ID is set
     *  * Update row if ID is set
     *
     * Cannot be used on tables/entities where there's no ID parameter
     *
     * @param Entity $entity
     * @param string $column_match
     * @return array|null|int
     * @throws \Exception
     */
    public function save($entity, $column_match = null)
    {
        if (!is_callable(array($entity, 'getId'))) {
            throw new \Exception("Cannot use save() on an entity without a getId method");
        }

        if ($entity->getId()) {
            $this->update($entity, $column_match);
            return $entity->getId();
        }
        else {
            return $this->insert($entity);
        }
    }

    /**
     * Insert the data from an entity into the database table
     *
     * @param Entity|array $data The entity or an array of entities
     * @return array|string
     */
    public function insert($data)
    {
        $this->event_dispatcher->dispatch('model.insert', $event = new ModelInsertEvent($data));
        $data = $event->getData();

        return $this->query()->insert($data);
    }

    /**
     * Use the data from an entity to update the database table
     *
     * @param Entity $entity
     * @param string|array $column_match Set the columns that must match to update on.
     *        By default will update row with matching 'id' field
     *
     * @throws \Exception
     */
    public function update(Entity $entity, $column_match = null)
    {
        $this->event_dispatcher->dispatch('model.update', new ModelUpdateEvent($entity));

        if (!$column_match) {
            $column_match = $this->number_identifier_column;
        }

        $query = $this->createWhereQuery($entity, $column_match);

        $query->update($entity);
    }

    /**
     * Delete a row from the database using the matching parameter(s) of an entity
     *
     * @param Entity $entity
     * @param string $column_match
     * @throws \Exception
     */
    public function delete(Entity $entity, $column_match = null)
    {
        if (!$column_match) {
            $column_match = $this->identifier_column;
        }

        $query = $this->createWhereQuery($entity, $column_match);

        $query->delete();
    }

    /**
     * Create a WHERE query from an entity and it's values
     *
     * For example, if the entity has an 'id' field and $column_match contains 'id'
     * a WHERE will be generated for WHERE id = $entity->getId();
     *
     * @param $entity
     * @param $column_match
     *
     * @throws \Exception
     * @return QueryBuilderHandler
     */
    protected function createWhereQuery($entity, $column_match)
    {
        $query = $this->query();

        $columns = (array) $column_match;

        foreach ($columns as $column) {
            $getter = 'get'.str_replace('_', '', $column);

            if (!method_exists($entity, $getter)) {
                throw new \Exception('Cannot generate where query, entity does not have a '.$getter.' method');
            }

            $query->where($column, $entity->$getter());
        }

        return $query;
    }

    /**
     * Create a new entity from the getEntity() method and assign any sub-entities that
     * have been assigned to this Model
     *
     * @return mixed
     */
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

    /**
     * Delete a row by ID
     *
     * @param $id
     */
    public function deleteById($id)
    {
        $this->query()->where($this->identifier_column, $id)->delete();
    }

    /**
     * Delete multiple rows by ID
     *
     * @param $ids
     */
    public function deleteByIds(array $ids)
    {
        $this->query()->whereIn('id', $ids)->delete();
    }

    /**
     * Get the column name that this model looks for when joined onto another
     *
     * @param null $table The table that is being joined. Allows for different columns to be specified for different tables.
     * @return string
     */
    public function getJoinColumn($table = null) // todo: support alternate column names
    {
        // Default join column
        return $this->getSingular().'_id';
    }

    /**
     * Add a sub-entity that will contain data that has been added to the table
     * using an extension
     *
     * @param $overflow_name
     * @param $class_name
     */
    public function addOverflowEntity($overflow_name, $class_name)
    {
        $this->sub_entities[$overflow_name] = array('class' => $class_name, 'type' => 'overflow');
    }
}