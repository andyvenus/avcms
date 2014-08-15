<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 13:55
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Yaml\Yaml;

class Bundles implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('bundle_manager', 'AVCMS\Core\Bundle\BundleManager')
            ->setArguments(array('%dev_mode%'));

        $container->register('listener.controller.inject.bundle', 'AVCMS\Core\Bundle\Listeners\ControllerInjectBundle')
            ->setArguments(array(new Reference('bundle_manager')))
            ->addTag('event.subscriber')
        ;
    }
}