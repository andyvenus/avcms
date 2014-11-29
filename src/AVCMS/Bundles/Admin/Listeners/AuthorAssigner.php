<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 16:00
 */

namespace AVCMS\Bundles\Admin\Listeners;

use AVCMS\Bundles\Users\ActiveUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AuthorAssigner implements EventSubscriberInterface
{
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function setDates($event)
    {
        $entity = $event->getEntity();

        if (!is_callable(array($entity, 'getId'))) {
            return;
        }

        $userId = $this->tokenStorage->getToken()->getUser()->getId();

        if ($entity->getId() === null && is_callable(array($entity, 'setCreatorId')) && is_callable(array($entity, 'getCreatorId')) && $entity->getCreatorId() === null) {
            $entity->setCreatorId($userId);
        }

        if ($entity->getId() !== null && is_callable(array($entity, 'setEditorId'))) {
            $entity->setEditorId($userId);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'admin.before.content.save' => array('setDates', 8),
        );
    }
} 