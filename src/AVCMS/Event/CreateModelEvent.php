<?php

namespace AVCMS\Event;

use Symfony\Component\EventDispatcher\Event;
use AVCMS\Model\Model;

class CreateModelEvent extends Event
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }
}