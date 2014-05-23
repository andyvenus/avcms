<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:18
 */

namespace AVCMS\Bundles\Assets;

use AVCMS\Core\AssetManager\Asset\BundleFileAsset;
use AVCMS\Core\AssetManager\AssetManager;
use AVCMS\Core\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AssetsBundle extends Bundle
{

    public function setUp()
    {

    }

    public function modifyContainer(ContainerBuilder $container)
    {

    }

    public function routes(RouteCollection $routes)
    {
        $routes->add('bundle_asset', new Route('/bundle_asset/{bundle}/{type}/{asset_file}', array(
            '_controller' => 'AVCMS\\Bundles\\Assets\\Controller\\AssetsController::getAssetAction',
        )));

        $routes->add('template_asset', new Route('/template_asset/{template}/{type}/{asset_file}', array(
            '_controller' => 'AVCMS\\Bundles\\Assets\\Controller\\AssetsController::getAssetAction',
        )));

        $routes->add('generate_assets', new Route('/generate_assets', array(
            '_controller' => 'AVCMS\\Bundles\\Assets\\Controller\\AssetsController::generateAssetsAction',
        )));
    }

    public function getId()
    {
        return 'Assets';
    }

    public function bundleInfo()
    {
        return array(
            'short_name' => $this->getId(),
            'full_name' => 'AVCMS Assets Manager',
            'description' => 'Bundle for assetic asset management, simplified',
            'version' => '0.0.1 Alpha'
        );
    }
} 