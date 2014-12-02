<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:27
 */

namespace AV\Kernel\Bundle;

use AV\Kernel\Bundle\Config\BundleConfigValidator;
use AV\Kernel\Bundle\Exception\NotFoundException;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

class BundleManager implements BundleManagerInterface
{

    /**
     * @var array
     */
    protected $bundles;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

    /**
     * @var bool
     */
    protected $bundlesInitialized = false;

    /**
     * @var array
     */
    protected $bundleLocations;

    /**
     * @var array
     */
    protected $bundleConfigs;

    /**
     * @var bool
     */
    protected $cacheIsFresh = true;

    /**
     * @var ConfigurationInterface
     */
    protected $configValidator;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var ConfigCache
     */
    protected $configCache;

    public function __construct(array $bundleLocations, $configDir = 'app/config', $cacheDir = 'cache')
    {
        $this->bundleLocations = $bundleLocations;
        $this->configDir = $configDir;
        $this->cacheDir = $cacheDir;

        $configValidator = new BundleConfigValidator();
        $configValidator->setBundleDirs($bundleLocations);

        $this->configValidator = $configValidator;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    public function isDebug()
    {
        return $this->debug;
    }

    /**
     *  Load up the bundles using the combined config, create the
     *  BundleConfig instances
     */
    public function initBundles()
    {
        $bundlesConfig = $this->loadAppBundleConfig();

        foreach ($bundlesConfig as $bundleName => $bundle) {
            if (isset($bundle['enabled']) && $bundle['enabled'] === true) {
                $bundleConfig = new BundleConfig($bundle);
                $this->bundleConfigs[$bundleName] = $bundleConfig;
            }
        }

        $this->bundlesInitialized = true;
    }

    /**
     * Add required services to the container
     *
     * @param ContainerBuilder $container
     * @throws NotFoundException
     */
    public function decorateContainer(ContainerBuilder $container)
    {
        if ($this->bundlesInitialized == false) {
            $this->initBundles();
        }

        foreach ($this->bundleConfigs as $bundleConfig) {
            if ($bundleConfig->container_params && !empty($bundleConfig->container_params) && $bundleConfig->ignore_services !== true) {
                foreach ($bundleConfig['container_params'] as $param => $val) {
                    $container->setParameter($param, $val);
                }
            }

            if ($bundleConfig->services && !empty($bundleConfig->services) && $bundleConfig->ignore_services !== true) {
                foreach ($bundleConfig->services as $serviceClass) {
                    $fullyQualifiedClass = $bundleConfig->namespace.'\\Services\\'.$serviceClass;

                    if (isset($bundleConfig['parent_bundle'])) {
                        $parentFullyQualifiedClass = $bundleConfig->parent_config->namespace.'\\Services\\'.$serviceClass;
                    }

                    if (class_exists($fullyQualifiedClass)) {
                        $service = new $fullyQualifiedClass();
                        $service->getServices($bundleConfig, $container);
                        $container->addObjectResource($fullyQualifiedClass);
                    }
                    elseif (isset($parentFullyQualifiedClass) && class_exists($parentFullyQualifiedClass)) {
                        $service = new $parentFullyQualifiedClass();
                        $service->getServices($bundleConfig, $container);
                        $container->addObjectResource($parentFullyQualifiedClass);
                    }
                    else {
                        throw new NotFoundException(sprintf("Service class %s not found", $fullyQualifiedClass));
                    }
                }
            }

            $container->addResource(new FileResource($bundleConfig->directory.'/config/bundle.yml'));
        }
    }

    public function setConfigCache(ConfigCache $configCache)
    {
        $this->configCache = $configCache;
    }

    /**
     * Load all bundle configuration files (config/bundle.yml)
     * from yaml or from config cache.
     *
     * @param bool $forceCacheRefresh
     * @throws Exception\NotFoundException
     * @throws \Exception
     * @return array|mixed
     */
    public function loadAppBundleConfig($forceCacheRefresh = false)
    {
        if (!file_exists($this->configDir.'/bundles.yml')) {
            throw new NotFoundException(sprintf("Bundles config could not be found: %", $this->configDir));
        }

        if ($this->debug) {
            $filenameAppend = '_dev';

            if (file_exists($this->cacheDir.'/bundle_config.php')) {
                unlink($this->cacheDir.'/bundle_config.php');
            }
        }
        else {
            $filenameAppend = '';
        }

        $cachePath = $this->cacheDir.'/bundle_config'.$filenameAppend.'.php';

        if (!$this->configCache) {
            $this->configCache = new ConfigCache($cachePath, $this->debug);
        }

        $bundlesConfigArray = array();

        $this->cacheIsFresh = $this->configCache->isFresh();

        if ((!$this->cacheIsFresh) || $forceCacheRefresh === true) {

            $appBundlesConfig = Yaml::parse($this->configDir.'/bundles.yml');

            if ($this->debug) {
                if (file_exists($this->configDir.'/bundles_dev.yml')) {
                    $appBundlesConfig = array_replace_recursive($appBundlesConfig, Yaml::parse($this->configDir.'/bundles_dev.yml'));
                }
            }

            $resources = array(new FileResource($this->configDir.'/bundles.yml'), new FileResource($this->configDir.'/bundles_dev.yml'));

            foreach ($appBundlesConfig as $bundleName => $appBundleConfig) {
                if ($appBundleConfig['enabled'] == true) {
                    $bundleLocation = $this->findBundleDirectory($bundleName);
                    $bundleConfigLocation = $bundleLocation.'/config/bundle.yml';

                    $resources[] = new FileResource($bundleConfigLocation);

                    $bundleConfig = Yaml::parse(file_get_contents($bundleConfigLocation));
                    $bundleConfig['enabled'] = true;
                    $bundleConfig['directory'] = $bundleLocation;

                    $appBundleConfigOverrides = array();
                    if (isset($appBundleConfig['config'])) {
                        $appBundleConfigOverrides = $appBundleConfig['config'];
                    }

                    if (isset($bundleConfig['parent_bundle'])) {
                        $parentConfig = $this->loadBundleConfig($bundleConfig['parent_bundle'])->getConfigArray();

                        if (isset($parentConfig['parent_bundle'])) {
                            throw new \Exception(sprintf("Cannot extend the Bundle %s as it extends another bundle itself", $bundleConfig['parent_bundle']));
                        }

                        $bundleConfig = array_replace_recursive($parentConfig, $bundleConfig);

                        $bundleConfig['parent_config'] = $parentConfig;
                    }

                    $mergedConfig = array_replace_recursive($bundleConfig, $appBundleConfigOverrides);

                    $configs = array($mergedConfig);

                    $processor = new Processor();

                    try {
                        $finalBundleConfig = $processor->processConfiguration($this->configValidator, $configs);
                    }
                    catch (\Exception $e) {
                        throw new \Exception("Config for bundle {$bundleConfig['name']} not configured correctly: ".$e->getMessage());
                    }

                    $bundlesConfigArray[$bundleName] = $finalBundleConfig;
                }
            }

            $this->configCache->write('<?php return '.var_export($bundlesConfigArray, true).';', $resources);
        }
        else {
            $bundlesConfigArray = require $cachePath;
        }

        return $bundlesConfigArray;
    }

    /**
     * Finds a bundle by searching the bundle locations
     *
     * @param $bundleName
     * @throws Exception\NotFoundException
     * @return string
     */
    public function findBundleDirectory($bundleName)
    {
        foreach ($this->bundleLocations as $location) {
            $configLocation = $location.'/'.$bundleName.'/config/bundle.yml';

            if (file_exists($configLocation)) {
                return $location.'/'.$bundleName;
            }
        }

        throw new NotFoundException(sprintf("Bundle directory not found for bundle %s", $bundleName));
    }

    /**
     * Explicitly load a bundle's bundle.yml file, no cache
     *
     * @param $bundle
     * @return BundleConfig|null
     * @throws NotFoundException
     */
    public function loadBundleConfig($bundle)
    {
        $directory = $this->findBundleDirectory($bundle);

        $configLocation = $directory.'/config/bundle.yml';

        $config = Yaml::parse($configLocation);
        $config['directory'] = $directory;

        return new BundleConfig($config);
    }

    /**
     * Get a bundle's config, load it if needed
     *
     * @param $bundle
     * @return BundleConfig|null
     */
    public function getBundleConfig($bundle)
    {
        if (isset($this->bundleConfigs[$bundle])) {
            return $this->bundleConfigs[$bundle];
        }
        else {
            return $this->loadBundleConfig($bundle);
        }
    }

    /**
     * @return array
     */
    public function getBundleConfigs()
    {
        return $this->bundleConfigs;
    }

    /**
     * @param $bundleName
     * @return bool
     */
    public function hasBundle($bundleName)
    {
        return isset($this->bundleConfigs[$bundleName]);
    }

    /**
     * @return bool
     */
    public function bundlesInitialized()
    {
        return $this->bundlesInitialized;
    }

    /**
     * @return array
     */
    public function getBundleLocations()
    {
        return $this->bundleLocations;
    }

    /**
     * @return bool
     */
    public function cacheIsFresh()
    {
        return $this->cacheIsFresh;
    }

    /**
     * @param RouteCollection $routes
     */
    public function getBundleRoutes(RouteCollection $routes)
    {
        foreach ($this->bundleConfigs as $bundleConfig) {
            if (file_exists($bundleConfig->directory . '/config/routes.yml') && $bundleConfig->ignore_routes !== true) {
                $locator = new FileLocator(array($bundleConfig->directory . '/config'));
                $loader = new YamlFileLoader($locator);

                $collection = $loader->load('routes.yml');
                $collection->addDefaults(array('_bundle' => $bundleConfig->name, '_avcms_env' => 'frontend'));
                $routes->addCollection($collection);

                if (file_exists($bundleConfig->directory . '/config/admin_routes.yml')) {
                    $adminCollection = $loader->load('admin_routes.yml');
                    $adminCollection->addDefaults(array('_bundle' => $bundleConfig->name, '_avcms_env' => 'admin'));
                    $adminCollection->addPrefix('admin');
                    $routes->addCollection($adminCollection);
                }
            }
        }
    }
}