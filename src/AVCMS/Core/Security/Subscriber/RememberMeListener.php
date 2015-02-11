<?php
/**
 * User: Andy
 * Date: 11/02/15
 * Time: 13:24
 */

namespace AVCMS\Core\Security\Subscriber;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RememberMeListener extends \Symfony\Component\Security\Http\Firewall\RememberMeListener
{
    public function handle(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        parent::handle($event);
    }
}
