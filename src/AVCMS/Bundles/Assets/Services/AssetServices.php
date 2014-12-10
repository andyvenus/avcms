<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:40
 */

namespace AVCMS\Bundles\Assets\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AssetServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('asset_manager', 'AVCMS\Core\AssetManager\AssetManager')
            ->addMethodCall('load', array(new Reference('assets.loader.bundles')))
            ->addMethodCall('load', array(new Reference('assets.loader.template')))
        ;

        $container->register('assets.loader.bundles', 'AVCMS\Core\Bundle\AssetLoader\BundleAssetLoader')
            ->setArguments(array(new Reference('bundle_manager'), new Reference('bundle.resource_locator')))
        ;

        $container->register('twig.asset_manager.extension', 'AVCMS\Core\AssetManager\Twig\AssetManagerExtension')
            ->setArguments(array('%dev_mode%', new Reference('asset_manager')))
            ->addTag('twig.extension')
        ;

        $container->register('public_file_mover', 'AVCMS\Core\Bundle\PublicFileMover')
            ->setArguments([new Reference('bundle_manager'), new Reference('template_manager'), '%cache_dir%', '%root_dir%'])
        ;

        $container->register('listener.bundle.public_file', 'AVCMS\Core\Bundle\Listeners\PublicFileSubscriber')
            ->setArguments(array(new Reference('public_file_mover')))
            ->addTag('event.subscriber')
        ;
    }
}