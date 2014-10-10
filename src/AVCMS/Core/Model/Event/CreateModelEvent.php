<?php

namespace AVCMS\Core\Model\Event;

use Symfony\Component\EventDispatcher\Event;
use AVCMS\Core\Model\Model;

class CreateModelEvent extends Event
{
    protected $model;

    protected $modelClass;

    public function __construct(Model $model, $modelClass)
    {
        $this->model = $model;
        $this->modelClass = $modelClass;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getModelClass()
    {
        return $this->model;
    }
}