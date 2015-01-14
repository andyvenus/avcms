<?php
/**
 * User: Andy
 * Date: 14/01/15
 * Time: 19:30
 */

namespace AVCMS\Bundles\Wallpapers\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class WallpaperCacheLimiterSubscriber implements EventSubscriberInterface
{
    public function cacheLimit(PostResponseEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        if ($request->get('_route') === 'wallpaper_image' && strpos($response->headers->get('Content-Type'), 'image') !== false) {

        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => ['cacheLimit']
        ];
    }
}
