<?php
/**
 * User: Andy
 * Date: 23/03/15
 * Time: 01:04
 */

namespace AVCMS\BundlesDev\DevTools\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class WhoopsListener
{
    public function onTerminate(GetResponseForExceptionEvent $event)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
        $whoops->writeToOutput(false);
        $whoops->allowQuit(false);

        $response = $whoops->handleException($event->getException());

        $event->setResponse(new Response($response));
    }
}
