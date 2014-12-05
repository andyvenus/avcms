<?php
/**
 * User: Andy
 * Date: 04/12/14
 * Time: 17:22
 */

namespace AVCMS\Core\Security;

use AVCMS\Core\Security\Exception\SiteOfflineException;
use AVCMS\Core\Security\Firewall\AccessDeniedHandler;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class SiteOfflineHandler
{
    private $accessDeniedHandler;

    public function __construct(AccessDeniedHandler $accessDeniedHandler)
    {
        $this->accessDeniedHandler = $accessDeniedHandler;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof SiteOfflineException) {
            $reponse = $this->accessDeniedHandler->handle($event->getRequest(), $event->getException());
            $event->setResponse($reponse);
        }
    }
} 