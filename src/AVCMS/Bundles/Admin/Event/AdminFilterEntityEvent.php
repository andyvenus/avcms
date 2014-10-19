<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 16:23
 */

namespace AVCMS\Bundles\Admin\Event;

use AV\Model\Entity;
use Symfony\Component\EventDispatcher\Event;

class AdminFilterEntityEvent extends Event
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