<?php
/**
 * User: Andy
 * Date: 23/09/2014
 * Time: 20:21
 */

namespace AVCMS\Core\Security\Subscriber;

use AV\Model\Model;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AnonymousAuthenticationSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;
    private $key;
    private $logger;
    private $groupsModel;
    private $usersModel;

    public function __construct(TokenStorageInterface $tokenStorage, $key, Model $usersModel, Model $groupsModel, LoggerInterface $logger = null)
    {
        $this->usersModel = $usersModel;
        $this->groupsModel = $groupsModel;
        $this->tokenStorage = $tokenStorage;
        $this->key = $key;
        $this->logger = $logger;
    }

    public function handle(GetResponseEvent $event)
    {
        if (null !== $this->tokenStorage->getToken()) {
            return;
        }

        $user = $this->usersModel->newEntity();
        $user->setUsername("Unregistered");
        $user->setRoleList('ROLE_UNREGISTERED');
        $user->group = $this->groupsModel->getOne('ROLE_UNREGISTERED');

        $this->tokenStorage->setToken(new AnonymousToken($this->key, $user, array()));

        if (null !== $this->logger) {
            $this->logger->info('Populated SecurityContext with an anonymous Token');
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('handle', -100)
        );
    }
}