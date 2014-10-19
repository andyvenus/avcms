<?php

namespace AV\Model;

use AV\Model\Event\CreateModelEvent;

class ModelFactory {

    /**
     * @var string;
     */

    protected $query_builder;

    protected $aliases;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $event_dispatcher;

    public function __construct($query_builder, $event_dispatcher, $taxonomy_manager = null)
    {
        $this->query_builder = $query_builder;
        $this->event_dispatcher = $event_dispatcher;
        $this->taxonomy_manager = $taxonomy_manager;
    }

    public function addModelAlias($alias, $class)
    {
        $this->aliases[$alias] = $class;
    }

    /**
     * @param $model_class
     * @throws \Exception
     * @return Model
     */
    public function create($model_class)
    {

        if (strpos($model_class,'@') !== false) {
            $alias = str_replace('@', '', $model_class);
            if (isset($this->aliases[$alias])) {
                $model_class = $this->aliases[$alias];
            }
            else {
                throw new \Exception("Model alias '$model_class' not found");
            }
        }

        /**
         * @var $model Model
         */
        $model = new $model_class($this->query_builder, $this->event_dispatcher);
        if ($this->taxonomy_manager) {
            $model->setTaxonomyManager($this->taxonomy_manager);
        }

        $new_model_event = new CreateModelEvent($model, $model_class);
        $this->event_dispatcher->dispatch('model.create', $new_model_event);

        return $model;
    }
} 