<?php
/**
 * User: Andy
 * Date: 14/07/2014
 * Time: 19:16
 */

namespace AV\Kernel\Listeners;

use AV\Kernel\Bundle\BundleManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerInjectBundle implements EventSubscriberInterface
{
    /**
     * @var BundleManagerInterface
     */
    protected $bundleManager;

    public function __construct(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    public function injectBundle(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->has('_bundle')) {
            return;
        }

        $bundle = $request->get('_bundle');

        $bundleConfig = $this->bundleManager->getBundleConfig($bundle);

        $controller = $event->getController()[0];

        if (is_callable([$controller, 'setBundle'])) {
            $controller->setBundle($bundleConfig);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('injectBundle')
        );
    }
}
