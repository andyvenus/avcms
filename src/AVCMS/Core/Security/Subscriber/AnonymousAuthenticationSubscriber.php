<?php
/**
 * User: Andy
 * Date: 23/09/2014
 * Time: 20:21
 */

namespace AVCMS\Core\Security\Subscriber;

use AV\Model\Model;
use AVCMS\Bundles\Users\Model\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AnonymousAuthenticationSubscriber implements EventSubscriberInterface
{
    private $context;
    private $key;
    private $logger;
    private $groupsModel;

    public function __construct(SecurityContextInterface $context, $key, Model $groupsModel, LoggerInterface $logger = null)
    {
        $this->groupsModel = $groupsModel;
        $this->context = $context;
        $this->key = $key;
        $this->logger = $logger;
    }

    public function handle(GetResponseEvent $event)
    {
        if (null !== $this->context->getToken()) {
            return;
        }

        $user = new User();
        $user->setUsername("Unregistered");
        $user->setRoleList('ROLE_UNREGISTERED');
        $user->group = $this->groupsModel->getOne('ROLE_UNREGISTERED');

        $this->context->setToken(new AnonymousToken($this->key, $user, array()));

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