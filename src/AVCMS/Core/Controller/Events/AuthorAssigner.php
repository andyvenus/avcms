<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 16:00
 */

namespace AVCMS\Core\Controller\Events;

use AVCMS\Bundles\UsersBase\ActiveUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorAssigner implements EventSubscriberInterface
{
    protected $active_user;

    public function __construct(ActiveUser $active_user)
    {
        $this->active_user = $active_user;
    }

    public function setDates($event)
    {
        $entity = $event->getEntity();

        if (!is_callable(array($entity, 'getId'))) {
            return;
        }

        $user_id = $this->active_user->getUser()->getId();

        if ($entity->getId() === null && is_callable(array($entity, 'setCreatorId')) && is_callable(array($entity, 'getCreatorId')) && $entity->getCreatorId() === null) {
            $entity->setCreatorId($user_id);
        }

        if ($entity->getId() !== null && is_callable(array($entity, 'setEditorId'))) {
            $entity->setEditorId($user_id);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'admin.controller.filter.entity' => array('setDates', 8),
        );
    }
} 