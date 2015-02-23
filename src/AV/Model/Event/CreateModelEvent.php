<?php

namespace AV\Model\Event;

use AV\Model\Model;
use Symfony\Component\EventDispatcher\Event;

class CreateModelEvent extends Event
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $modelClass;

    public function __construct(Model $model, $modelClass)
    {
        $this->model = $model;
        $this->modelClass = $modelClass;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }
}
