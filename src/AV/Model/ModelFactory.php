<?php

namespace AV\Model;

use AV\Model\Event\CreateModelEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ModelFactory {

    /**
     * @var string;
     */
    protected $queryBuilder;

    /**
     * @var array
     */
    protected $aliases;

    /**
     * @var array
     */
    protected $cache = [];

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct($queryBuilder, EventDispatcherInterface $eventDispatcher)
    {
        $this->queryBuilder = $queryBuilder;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $alias
     * @param string $class
     */
    public function addModelAlias($alias, $class)
    {
        $this->aliases[$alias] = $class;
    }

    /**
     * @param string $modelClass
     * @throws \Exception
     * @return Model
     */
    public function create($modelClass)
    {
        if ($modelClass[0] === '@') {
            $alias = str_replace('@', '', $modelClass);
            if (isset($this->aliases[$alias])) {
                $modelClass = $this->aliases[$alias];
            }
            else {
                throw new \Exception("Model alias '$modelClass' not found");
            }
        }

        if (isset($this->cache[$modelClass])) {
            return $this->cache[$modelClass];
        }

        /**
         * @var $model Model
         */
        $model = $this->cache[$modelClass] = new $modelClass($this->queryBuilder, $this->eventDispatcher);

        $newModelEvent = new CreateModelEvent($model, $modelClass);
        $this->eventDispatcher->dispatch('model.create', $newModelEvent);

        return $model;
    }
} 
