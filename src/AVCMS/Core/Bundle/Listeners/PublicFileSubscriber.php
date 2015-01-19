<?php
/**
 * User: Andy
 * Date: 25/08/2014
 * Time: 10:55
 */

namespace AVCMS\Core\Bundle\Listeners;

use AVCMS\Core\Bundle\PublicFileMover;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class PublicFileSubscriber implements EventSubscriberInterface
{
    protected $bundleManager;

    protected $cacheDir;

    public function __construct(PublicFileMover $publicFileMover)
    {
        $this->publicFileMover = $publicFileMover;
    }

    public function moveFiles(GetResponseEvent $event)
    {
        if ($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST) {
            $this->publicFileMover->doMove();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('moveFiles')
        );
    }
}
