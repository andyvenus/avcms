<?php
/**
 * User: Andy
 * Date: 17/08/2014
 * Time: 19:49
 */

namespace AV\Kernel\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * An alias class so the bundle manager can be accessed
 * from the service container
 *
 * Class BundleManagerAlias
 * @package AVCMS\Core\Bundle
 */
final class BundleManagerAlias implements BundleManagerInterface
{
    /**
     * @var BundleManagerInterface
     */
    private $bundleManager;

    public function setBundleManager(BundleManagerInterface $bundle_manager)
    {
        $this->bundleManager = $bundle_manager;
    }

    /**
     * @return BundleManagerInterface
     * @throws \Exception
     */
    public function getBundleManager()
    {
        if (!isset($this->bundleManager)) {
            throw new \Exception("BundleManager not set");
        }

        return $this->bundleManager;
    }

    public function setDebug($debug)
    {
        $this->getBundleManager()->setDebug($debug);
    }

    public function isDebug()
    {
        return $this->getBundleManager()->isDebug();
    }

    public function hasBundle($bundleName)
    {
        return $this->getBundleManager()->hasBundle($bundleName);
    }

    public function getBundleLocations()
    {
        return $this->getBundleManager()->getBundleLocations();
    }

    public function loadBundleConfig($bundle)
    {
        return $this->getBundleManager()->loadBundleConfig($bundle);
    }

    public function getBundleConfig($bundle)
    {
        return $this->getBundleManager()->getBundleConfig($bundle);
    }

    public function onKernelBoot(ContainerInterface $container)
    {
        return $this->getBundleManager()->onKernelBoot($container);
    }

    public function loadAppBundleConfig()
    {
        return $this->getBundleManager()->loadAppBundleConfig();
    }

    public function decorateContainer(ContainerBuilder $container)
    {
        return $this->getBundleManager()->decorateContainer($container);
    }

    public function getBundleConfigs()
    {
        return $this->getBundleManager()->getBundleConfigs();
    }

    public function initBundles()
    {
        return $this->getBundleManager()->initBundles();
    }

    public function bundlesInitialized()
    {
        return $this->getBundleManager()->bundlesInitialized();
    }

    public function findBundleDirectory($bundleName)
    {
        return $this->getBundleManager()->findBundleDirectory($bundleName);
    }

    public function cacheIsFresh()
    {
        return $this->getBundleManager()->cacheIsFresh();
    }

    public function getBundleRoutes(RouteCollection $routeCollection)
    {
        return $this->getBundleManager()->getBundleRoutes($routeCollection);
    }
}