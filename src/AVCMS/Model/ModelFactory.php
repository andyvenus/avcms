<?php

namespace AVCMS\Model;

use AVCMS\Event\CreateModelEvent;

class ModelFactory {

    /**
     * @var string;
     */

    protected $container;

    public function __construct($container)
    {
        $this->container = $container;

        $this->query_builder = $this->container->get('query_builder')->getQueryBuilder();
        $this->event_dispatcher = $this->container->get('dispatcher');
    }

    /**
     * @param $model_class
     * @return Model
     */
    public function create($model_class) {

        $model = new $model_class($this->query_builder, $this->event_dispatcher);

        $new_model_event = new CreateModelEvent($model);
        $this->event_dispatcher->dispatch('model.create', $new_model_event);

        return $model;
    }
} 