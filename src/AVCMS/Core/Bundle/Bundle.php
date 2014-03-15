<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 13:34
 */

namespace AVCMS\Core\Bundle;

use AVCMS\Core\AssetManager\AssetManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollection;

abstract class Bundle implements BundleInterface {

    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function setUp()
    {

    }

    public function modifyContainer(ContainerBuilder $container)
    {

    }

    public function routes(RouteCollection $routes)
    {

    }

    public function templates()
    {

    }

    public function bundleInfo()
    {

    }

    public function assets(AssetManager $asset_manager)
    {

    }

    public function addTemplateDirectory($dir, $namespace = \Twig_Loader_Filesystem::MAIN_NAMESPACE)
    {
        $this->container->getDefinition('twig.filesystem')
            ->addMethodCall('addPath', array($dir, $namespace));
    }
} 