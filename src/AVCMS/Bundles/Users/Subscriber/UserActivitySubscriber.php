<?php
/**
 * User: Andy
 * Date: 21/11/14
 * Time: 13:22
 */

namespace AVCMS\Bundles\Users\Subscriber;

use AVCMS\Bundles\Users\Model\Users;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class UserActivitySubscriber implements EventSubscriberInterface
{
    /**
     * @var SecurityContextInterface
     */
    protected $tokenStorage;

    protected $users;

    public function __construct(TokenStorageInterface $tokenStorage, Users $users)
    {
        $this->tokenStorage = $tokenStorage;
        $this->users = $users;
    }

    public function updateUserActivity(PostResponseEvent $event)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user->getId() > 0) {
            $this->users->updateUserActivity($user->getId(), $event->getRequest()->getClientIp());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => ['updateUserActivity']
        ];
    }
}