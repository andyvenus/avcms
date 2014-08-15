<?php
/**
 * User: Andy
 * Date: 14/07/2014
 * Time: 19:16
 */

namespace AVCMS\Core\Bundle\Listeners;

use AVCMS\Core\Bundle\BundleManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerInjectBundle implements EventSubscriberInterface
{
    public function __construct(BundleManager $bundle_manager)
    {
        $this->bundle_manager = $bundle_manager;
    }

    public function injectBundle(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->has('_bundle')) {
            return;
        }

        $bundle = $request->get('_bundle');

        $bundle_config = $this->bundle_manager->getBundleConfig($bundle);

        $event->getController()[0]->setBundle($bundle_config);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('injectBundle')
        );
    }
}