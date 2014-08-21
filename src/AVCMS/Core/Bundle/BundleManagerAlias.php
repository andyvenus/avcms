<?php
/**
 * User: Andy
 * Date: 17/08/2014
 * Time: 19:49
 */

namespace AVCMS\Core\Bundle;

use AVCMS\Core\SettingsManager\SettingsManager;
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
    private $bundle_manager;

    public function setBundleManager(BundleManagerInterface $bundle_manager)
    {
        $this->bundle_manager = $bundle_manager;
    }

    /**
     * @return BundleManagerInterface
     * @throws \Exception
     */
    public function getBundleManager()
    {
        if (!isset($this->bundle_manager)) {
            throw new \Exception("BundleManager not set");
        }

        return $this->bundle_manager;
    }

    public function setDebug($debug)
    {
        $this->getBundleManager()->setDebug($debug);
    }

    public function isDebug()
    {
        $this->getBundleManager()->isDebug();
    }

    public function hasBundle($bundle_name)
    {
        return $this->getBundleManager()->hasBundle($bundle_name);
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

    public function findBundleDirectory($bundle_name)
    {
        return $this->getBundleManager()->findBundleDirectory($bundle_name);
    }

    public function cacheIsFresh()
    {
        return $this->getBundleManager()->cacheIsFresh();
    }

    public function getBundleRoutes(RouteCollection $route_collection)
    {
        return $this->getBundleManager()->getBundleRoutes($route_collection);
    }

    public function getBundleSettings(SettingsManager $settings_manager)
    {
        return $this->getBundleManager()->getBundleSettings($settings_manager);
    }
}