<?php
/**
 * User: Andy
 * Date: 17/08/2014
 * Time: 19:48
 */
namespace AV\Kernel\Bundle;

use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;

interface BundleManagerInterface
{
    /**
     * @param $bundleName
     * @return bool
     */
    public function hasBundle($bundleName);

    /**
     * @return array
     */
    public function getBundleLocations();

    /**
     * Explicitly load a bundle's bundle.yml file, no cache
     *
     * @param $bundle
     * @return BundleConfig|null
     * @throws \Exception
     */
    public function loadBundleConfig($bundle);

    /**
     * @param $debug
     * @return mixed
     */
    public function setDebug($debug);

    /**
     * @return mixed
     */
    public function isDebug();

    /**
     * Get a bundle's config, load it if needed
     *
     * @param $bundle
     * @return BundleConfig|null
     */
    public function getBundleConfig($bundle);

    /**
     * Load all bundle configuration files (config/bundle.yml)
     * from yaml or from config cache.
     *
     * @return array|mixed
     */
    public function loadAppBundleConfig();

    /**
     * Add required services to the container
     *
     * @param ContainerBuilder $container
     */
    public function decorateContainer(ContainerBuilder $container);

    /**
     * @return array
     */
    public function getBundleConfigs();

    /**
     *  Load up the bundles using the combined config, create the
     *  BundleConfig instances
     */
    public function initBundles();

    /**
     * @return bool
     */
    public function bundlesInitialized();

    /**
     * Finds a bundle by searching the bundle locations
     *
     * @param $bundleName
     * @return string
     */
    public function findBundleDirectory($bundleName);

    /**
     * @return bool
     */
    public function cacheIsFresh();

    /**
     * @param RouteCollection $routeCollection
     */
    public function getBundleRoutes(RouteCollection $routeCollection);
}