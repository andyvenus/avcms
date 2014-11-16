<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 15:34
 */

namespace AV\Model\Event;

use AV\Model\Entity;
use AV\Model\Model;
use Symfony\Component\EventDispatcher\Event;

class ModelUpdateEvent extends Event
{
    protected $entity;

    protected $model;

    public function __construct(Entity $entity, Model $model)
    {
        $this->entity = $entity;
        $this->model = $model;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getModel()
    {
        return $this->model;
    }
} 