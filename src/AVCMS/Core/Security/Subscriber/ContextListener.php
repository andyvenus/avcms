<?php
/**
 * User: Andy
 * Date: 26/09/2014
 * Time: 10:50
 */

namespace AVCMS\Core\Security\Subscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ContextListener as BaseContextListener;

class ContextListener extends BaseContextListener
{
    public function handle(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        parent::handle($event);
    }
}