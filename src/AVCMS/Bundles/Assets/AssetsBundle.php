<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:18
 */

namespace AVCMS\Bundles\Assets;

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
        $routes->add('bundle_asset', new Route('/asset/{bundle}/{type}/{asset}', array(
            '_controller' => 'AVCMS\\Bundles\\Assets\\Controller\\AssetsController::getAssetAction',
        )));

        $routes->add('generate_assets', new Route('/generate_assets', array(
            '_controller' => 'AVCMS\\Bundles\\Assets\\Controller\\AssetsController::generateAssetsAction',
        )));
    }

    public function bundleInfo()
    {
        return array(
            'short_name' => 'Assets',
            'full_name' => 'AVCMS Assets Manager',
            'description' => 'Bundle for assetic asset management, simplified',
            'version' => '0.0.1 Alpha'
        );
    }
} 