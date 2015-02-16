<?php
/**
 * User: Andy
 * Date: 16/02/15
 * Time: 22:05
 */

namespace AVCMS\Core\Security\Subscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ContextListener extends \Symfony\Component\Security\Http\Firewall\ContextListener
{
    public function handle(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            parent::handle($event);
        }
    }
}
