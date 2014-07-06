<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:18
 */

namespace AVCMS\Bundles\Admin;

use AVCMS\Core\AssetManager\AssetManager;
use AVCMS\Core\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AdminBundle extends Bundle
{

    public function setUp()
    {
    }

    public function modifyContainer(ContainerBuilder $container)
    {

    }

    public function routes(RouteCollection $routes)
    {
        $routes->add('username_suggest', new Route('/admin/username_suggest', array(
            '_controller' => 'AVCMS\\Bundles\\Admin\\Controller\\AdminBundleController::usernameSuggestAction',
        )));

        $routes->add('generate_slug', new Route('/admin/generate_slug/{title}', array(
            '_controller' => 'AVCMS\\Bundles\\Admin\\Controller\\AdminBundleController::slugGeneratorAction',
        )));
    }

    public function assets(AssetManager $asset_manager)
    {
    }

    public function bundleInfo()
    {
        return array(
            'short_name' => 'Admin',
            'full_name' => 'AVCMS Admin Bundle',
            'description' => 'A base admin bundle',
            'version' => '0.0.1 Alpha'
        );
    }
} 