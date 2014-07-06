<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 15:34
 */

namespace AVCMS\Core\Model\Event;

use AVCMS\Core\Model\Entity;
use Symfony\Component\EventDispatcher\Event;

class ModelUpdateEvent extends Event
{
    protected $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }
} 