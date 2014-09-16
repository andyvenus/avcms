<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:27
 */

namespace AVCMS\Core\Bundle;

use AVCMS\Core\Bundle\Config\BundleConfigurationValidator;
use AVCMS\Core\Bundle\Exception\NotFoundException;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    public function __construct(array $bundleLocations, $configDir = 'app/config', $cacheDir = 'cache', ConfigurationInterface $configValidator = null)
    {
        $this->bundleLocations = $bundleLocations;
        $this->configDir = $configDir;
        $this->cacheDir = $cacheDir;

        if ($configValidator == null) {
            $configValidator = new BundleConfigurationValidator();
        }

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
                $bundle_config = new BundleConfig($this, $bundle);
                $this->bundleConfigs[$bundleName] = $bundle_config;
            }
        }

        $this->bundlesInitialized = true;
    }

    /**
     * Add required services to the container
     *
     * @param ContainerBuilder $container
     * @throws Exception\NotFoundException
     */
    public function decorateContainer(ContainerBuilder $container)
    {
        if ($this->bundlesInitialized == false) {
            $this->initBundles();
        }

        foreach ($this->bundleConfigs as $bundleConfig) {
            if ($bundleConfig->services && !empty($bundleConfig->services) && $bundleConfig->ignore_services !== true) {
                foreach ($bundleConfig->services as $service_class) {
                    $fullyQualifiedClass = $bundleConfig->namespace.'\\Services\\'.$service_class;
                    if (class_exists($fullyQualifiedClass)) {
                        $service = new $fullyQualifiedClass();
                        $service->getServices(array(), $container);
                        $container->addObjectResource($fullyQualifiedClass);
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
     * @param bool $force_cache_refresh
     * @throws Exception\NotFoundException
     * @throws \Exception
     * @return array|mixed
     */
    public function loadAppBundleConfig($force_cache_refresh = false)
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

        if ((!$this->cacheIsFresh) || $force_cache_refresh === true) {

            $appBundlesConfig = Yaml::parse($this->configDir.'/bundles.yml');

            if ($this->debug) {
                if (file_exists($this->configDir.'/bundles_dev.yml')) {
                    $appBundlesConfig = array_replace_recursive($appBundlesConfig, Yaml::parse($this->configDir.'/bundles_dev.yml'));
                }
            }

            $resources = array(new FileResource($this->configDir.'/bundles.yml'), new FileResource($this->configDir.'/bundles_dev.yml'));

            foreach ($appBundlesConfig as $bundleName => $appBundleConfig) {
                if ($appBundleConfig['enabled'] == true) {
                    $bundle_location = $this->findBundleDirectory($bundleName);
                    $bundleConfigLocation = $bundle_location.'/config/bundle.yml';

                    $resources[] = new FileResource($bundleConfigLocation);

                    $bundleConfig = Yaml::parse(file_get_contents($bundleConfigLocation));
                    $bundleConfig['enabled'] = true;
                    $bundleConfig['directory'] = $bundle_location;

                    $appBundleConfigOverrides = array();
                    if (isset($appBundleConfig['config'])) {
                        $appBundleConfigOverrides = $appBundleConfig['config'];
                    }

                    // todo: parent bundle outside of BundleConfig for caching
                    if (isset($bundleConfig['parent_bundle'])) {

                    }

                    $merged_config = array_replace_recursive($bundleConfig, $appBundleConfigOverrides);

                    $configs = array($merged_config);

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
        $bundle_config = new BundleConfig($this, $config);

        return $bundle_config;

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

                $collection->addDefaults(array('_bundle' => $bundleConfig->name));

                $routes->addCollection($collection);
            }
        }
    }
}