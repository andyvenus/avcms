<?php
/**
 * User: Andy
 * Date: 15/08/2014
 * Time: 12:28
 */

namespace AVCMS\Core\Bundle\Listeners;

use AVCMS\Core\Bundle\BundleManager;
use AVCMS\Core\Kernel\Events\KernelBootEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BundleSubscriber implements EventSubscriberInterface
{
    public function __construct(BundleManager $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    public function onKernelBootEvent(KernelBootEvent $event)
    {
        $container = $event->getContainer();
        $this->bundleManager->onKernelBoot($container);
    }

    public static function getSubscribedEvents()
    {
        return array(
            'kernel.boot' => array('onKernelBootEvent')
        );
    }
}