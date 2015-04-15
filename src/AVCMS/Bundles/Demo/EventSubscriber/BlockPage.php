<?php
/**
 * User: Andy
 * Date: 15/04/15
 * Time: 14:20
 */

namespace AVCMS\Bundles\Demo\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class BlockPage implements EventSubscriberInterface
{
    public function block(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $route = $request->get('_route');

        $blocked = ['generate_assets', 'check_for_updates', 'updates'];

        if (in_array($route, $blocked)) {
            $event->setResponse(new Response('Sorry this page is blocked in the demo'));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['block']
        ];
    }
}
