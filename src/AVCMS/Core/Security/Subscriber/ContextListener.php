<?php
/**
 * User: Andy
 * Date: 18/02/15
 * Time: 12:54
 */

namespace AVCMS\Core\Security\Subscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ContextListener extends \Symfony\Component\Security\Http\Firewall\ContextListener
{
    private $contextKey;

    public function __construct(TokenStorageInterface $tokenStorage, array $userProviders, $contextKey, LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct($tokenStorage, $userProviders, $contextKey, $logger, $dispatcher);
        $this->contextKey = $contextKey;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->hasPreviousSession() ? $request->getSession() : null;

        if ($event->isMasterRequest() || $session === null || $session->get('_security_'.$this->contextKey)) {
            parent::handle($event);
        }
    }
}
