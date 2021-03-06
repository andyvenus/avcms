<?php

namespace AV\Model;

use AV\Model\Event\ModelInsertEvent;
use AV\Model\Event\ModelUpdateEvent;
use AV\Model\QueryBuilder\QueryBuilderHandler;
use AVCMS\Core\Taxonomy\TaxonomyManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Model
 * @package AV\Model
 */
abstract class Model {

    /**
     * @var $query_builder QueryBuilderHandler
     */
    protected $queryBuilder;

    /**
     * @var integer
     */
    protected $lastInsertId;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var array Sub entities that are auto-added to the base entity when newEntity() is called
     */
    protected $subEntities = array();

    /**
     * @var string
     */
    protected $numberIdentifierColumn = 'id';

    /**
     * @var string
     */
    protected $textIdentifierColumn = 'id';

    /**
     * @var string
     */
    protected $finder = 'AV\Model\Finder';

    /**
     * @var \AVCMS\Core\Taxonomy\TaxonomyManager
     */
    protected $taxonomyManager = null;

    /**
     * @param QueryBuilderHandler $queryBuilder
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(QueryBuilderHandler $queryBuilder, EventDispatcherInterface $eventDispatcher)
    {
        $this->queryBuilder = $queryBuilder;
        $this->eventDispatcher = $eventDispatcher;
    }

    abstract public function getTable();

    abstract public function getSingular();

    abstract public function getEntity();

    /**
     * Set a taxonomy manager allowing records to be found by assigned taxonomy
     *
     * @param TaxonomyManager $taxonomyManager
     */
    public function setTaxonomyManager(TaxonomyManager $taxonomyManager)
    {
        $this->taxonomyManager = $taxonomyManager;
    }

    /**
     * @return \AV\Model\Finder|mixed
     */
    public function find()
    {
        $finder = new $this->finder($this, $this->taxonomyManager);
        $finder->setSortOptions($this->getFinderSortOptions());

        return $finder;
    }

    public function getFinderSortOptions()
    {
        return array(
            'newest' => 'id DESC',
            'oldest' => 'id ASC'
        );
    }

    /**
     * Create a query using this model's information and return it
     *
     * @return QueryBuilderHandler
     */
    public function query()
    {
        $query = $this->queryBuilder->modelQuery($this);

        return $query;
    }

    /**
     * Find a row by ID, return the query builder for further modification
     *
     * @param $id
     * @return QueryBuilderHandler
     */
    public function findOne($id = null)
    {
        return $this->query()->where($this->getTable().'.'.$this->numberIdentifierColumn, $id);
    }

    /**
     * Find a row by ID and return the matching entity
     *
     * @param $id
     * @return Entity|mixed
     */
    public function getOne($id)
    {
        return $this->findOne($id)->first();
    }

    /**
     * Return an array of entities matching a given array of ids
     *
     * @param array $ids
     * @return Entity|mixed
     */
    public function getIds(array $ids)
    {
        return $this->query()->whereIn($this->getTable().'.'.$this->numberIdentifierColumn, $ids)->get();
    }

    /**
     * Get one result by ID or get a new entity if $id is 0 (or null etc)
     *
     * @param $id
     * @return Entity|mixed
     */
    public function getOneOrNew($id)
    {
        if ($id) {
            return $this->getOne($id);
        }
        else {
            return $this->newEntity();
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
     * @param string $columnMatch
     * @return array|null|int|string
     * @throws \Exception
     */
    public function save(Entity $entity, $columnMatch = null)
    {
        if (is_callable(array($entity, 'get'.$this->numberIdentifierColumn))) {
            $idMethod = 'get'.$this->numberIdentifierColumn;
        }
        elseif (is_callable(array($entity, 'get'.$this->textIdentifierColumn))) {
            $idMethod = 'get'.$this->textIdentifierColumn;
        }
        else {
            throw new \Exception("Cannot save, entity does not have a valid getter for {$this->numberIdentifierColumn} or {$this->textIdentifierColumn}");
        }

        if ($entity->$idMethod()) {
            $this->update($entity, $columnMatch);
            return $entity->$idMethod();
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
        $this->eventDispatcher->dispatch('model.insert', $event = new ModelInsertEvent($data, $this));
        $data = $event->getData();

        return $this->query()->insert($data);
    }

    /**
     * Use the data from an entity to update the database table
     *
     * @param Entity $entity
     * @param string|array $columnMatch Set the columns that must match to update on.
     *        By default will update row with matching 'id' field
     *
     * @throws \Exception
     */
    public function update(Entity $entity, $columnMatch = null)
    {
        $this->eventDispatcher->dispatch('model.update', new ModelUpdateEvent($entity, $this));

        if (!$columnMatch) {
            $columnMatch = $this->numberIdentifierColumn;
        }

        $query = $this->createWhereQuery($entity, $columnMatch);

        $query->update($entity);
    }

    /**
     * Delete a row from the database using the matching parameter(s) of an entity
     *
     * @param Entity $entity
     * @param string $columnMatch
     * @throws \Exception
     */
    public function delete(Entity $entity, $columnMatch = null)
    {
        if (!$columnMatch) {
            $columnMatch = $this->numberIdentifierColumn;
        }

        $query = $this->createWhereQuery($entity, $columnMatch);

        $query->delete();
    }

    /**
     * Create a WHERE query from an entity and it's values
     *
     * For example, if the entity has an 'id' field and $columnMatch contains 'id'
     * a WHERE will be generated for WHERE id = $entity->getId();
     *
     * @param Entity $entity
     * @param $columnMatch
     *
     * @throws \Exception
     * @return QueryBuilderHandler
     */
    protected function createWhereQuery(Entity $entity, $columnMatch)
    {
        $query = $this->query();

        $columns = (array) $columnMatch;

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
     * @return mixed|Entity
     */
    public function newEntity()
    {
        $entityName = $this->getEntity();
        $entity = new $entityName();

        foreach ($this->subEntities as $subEntityName => $sub_entity) {
            $subEntityInstance = new $sub_entity['class']();

            $entity->addSubEntity($subEntityName, $subEntityInstance, true);
        }
        return $entity;
    }

    /**
     * Delete a row by ID
     *
     * @param $ids
     */
    public function deleteById($ids)
    {
        $ids = (array) $ids;
        $this->query()->whereIn($this->numberIdentifierColumn, $ids)->delete();
    }

    /**
     * Get the column name that this model looks for when joined onto another
     *
     * @param null $table The table that is being joined. Allows for different columns to be specified for different tables.
     * @return string
     */
    public function getJoinColumn($table = null)
    {
        return $this->getSingular().'_id';
    }

    /**
     * Add a sub-entity that will contain data that has been added to the table
     * using an extension
     *
     * @param $overflowName
     * @param $className
     */
    public function addOverflowEntity($overflowName, $className)
    {
        $this->subEntities[$overflowName] = array('class' => $className, 'type' => 'overflow');
    }

    public function getAll()
    {
        return $this->query()->get();
    }

    public function newest($amount)
    {
        return $this->query()->limit($amount)->get();
    }
}
