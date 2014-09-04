<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:40
 */

namespace AVCMS\BundlesDev\Assets\Services;

use AVCMS\Core\AssetManager\Asset\AppFileAsset;
use AVCMS\Core\AssetManager\AssetManager;
use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AssetServices implements Service
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
    }
}