<?php
/**
 * User: Andy
 * Date: 19/10/14
 * Time: 23:55
 */

namespace AV\Bundles\Framework\Services;

use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BundleServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('bundle_manager', 'AV\Kernel\Bundle\BundleManagerAlias');

        $container->register('listener.controller.inject.bundle', 'AV\Kernel\Listeners\ControllerInjectBundle')
            ->setArguments(array(new Reference('bundle_manager')))
            ->addTag('event.subscriber')
        ;

        $container->register('bundle.resource_locator', 'AV\Kernel\Bundle\ResourceLocator')
            ->setArguments(array(new Reference('bundle_manager'), new Reference('settings_manager')))
        ;
    }
}