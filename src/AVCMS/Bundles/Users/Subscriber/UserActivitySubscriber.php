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

class UserActivitySubscriber implements EventSubscriberInterface
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    protected $users;

    public function __construct(SecurityContextInterface $securityContext, Users $users)
    {
        $this->securityContext = $securityContext;
        $this->users = $users;
    }

    public function updateUserActivity(PostResponseEvent $event)
    {
        $user = $this->securityContext->getToken()->getUser();

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