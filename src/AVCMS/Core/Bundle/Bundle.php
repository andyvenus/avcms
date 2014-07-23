<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 13:34
 */

namespace AVCMS\Core\Bundle;

use AVCMS\Core\AssetManager\AssetManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouteCollection;

abstract class Bundle implements BundleInterface {

    protected $container;

    protected $config;

    public function __construct(BundleConfig $config, ContainerBuilder $container)
    {
        $this->config = $config;
        $this->container = $container;

        $this->setUp();
        // if $config->container === true
        $this->modifyContainer($this->container);
        $this->routes($this->container->getParameter('routes'));
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

    protected function addEventListener($eventName, $listener, $priority = 0)
    {
        $this->container->get('dispatcher')->addListener($eventName, $listener, $priority = 0);
    }

    protected function addEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->container->get('dispatcher')->addSubscriber($subscriber);
    }
} 