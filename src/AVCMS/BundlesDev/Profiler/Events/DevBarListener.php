<?php
/**
 * User: Andy
 * Date: 30/07/2014
 * Time: 13:26
 */

namespace AVCMS\BundlesDev\Profiler\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DevBarListener implements  EventSubscriberInterface
{
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function kernelEventListener(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        if (($token = $response->headers->get('X-Debug-Token')) && (strpos($request->getUri(), 'dev/') === false)) {
            $content = $response->getContent();

            if (strripos($content, '</body>') !== false) {
                $dev_bar = $this->twig->render('@Profiler/dev_bar_container.twig', array('token' => $token));

                $content = str_ireplace('</body>', "$dev_bar</body>", $content);

                $response->setContent($content);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array('kernelEventListener', -125)
        );
    }
}