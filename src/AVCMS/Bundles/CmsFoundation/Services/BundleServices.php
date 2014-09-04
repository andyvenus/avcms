<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 13:55
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BundleServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('bundle_manager', 'AVCMS\Core\Bundle\BundleManagerAlias');

        $container->register('listener.controller.inject.bundle', 'AVCMS\Core\Bundle\Listeners\ControllerInjectBundle')
            ->setArguments(array(new Reference('bundle_manager')))
            ->addTag('event.subscriber')
        ;

        $container->register('bundle.resource_locator', 'AVCMS\Core\Bundle\ResourceLocator')
            ->setArguments(array(new Reference('bundle_manager'), new Reference('settings_manager')))
        ;

        $container->register('settings.loader.bundle', 'AVCMS\Core\Bundle\SettingsLoader\BundleSettingsLoader')
            ->setArguments(array(new Reference('bundle_manager')))
        ;

        $container->register('listener.bundle.public_file', 'AVCMS\Core\Bundle\Listeners\PublicFileSubscriber')
            ->setArguments(array(new Reference('bundle_manager')))
            ->addTag('event.subscriber')
        ;
    }
}