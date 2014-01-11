<?php

namespace AVCMS\Core\Model;

use AVCMS\Core\Event\CreateModelEvent;

class ModelFactory {

    /**
     * @var string;
     */

    protected $query_builder;

    protected $event_dispatcher;

    public function __construct($query_builder, $event_dispatcher)
    {
        $this->query_builder = $query_builder->getQueryBuilder();
        $this->event_dispatcher = $event_dispatcher;
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