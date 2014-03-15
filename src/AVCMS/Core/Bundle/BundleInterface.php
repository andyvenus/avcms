<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 12:10
 */
namespace AVCMS\Core\Bundle;

use AVCMS\Core\AssetManager\AssetManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollection;

interface BundleInterface
{
    public function setUp();

    public function routes(RouteCollection $routes);

    public function modifyContainer(ContainerBuilder $container);

    public function bundleInfo();

    public function assets(AssetManager $asset_manager);
}