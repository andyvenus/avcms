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
    protected $bundles_initialized = false;

    /**
     * @var array
     */
    protected $bundle_locations;

    /**
     * @var array
     */
    protected $bundle_configs;

    /**
     * @var bool
     */
    protected $cache_fresh = true;

    /**
     * @var ConfigurationInterface
     */
    protected $config_validator;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var string
     */
    protected $cache_dir;

    /**
     * @var ConfigCache
     */
    protected $config_cache;

    public function __construct(array $bundle_locations, $config_dir = 'app/config', $cache_dir = 'cache', ConfigurationInterface $config_validator = null)
    {
        $this->bundle_locations = $bundle_locations;
        $this->config_dir = $config_dir;
        $this->cache_dir = $cache_dir;

        if ($config_validator == null) {
            $config_validator = new BundleConfigurationValidator();
        }

        $this->config_validator = $config_validator;
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
        $bundles_config = $this->loadAppBundleConfig();

        foreach ($bundles_config as $bundle_name => $bundle) {
            if (isset($bundle['enabled']) && $bundle['enabled'] === true) {
                $bundle_config = new BundleConfig($this, $bundle);
                $this->bundle_configs[$bundle_name] = $bundle_config;
            }
        }

        $this->bundles_initialized = true;
    }

    /**
     * Add required services to the container
     *
     * @param ContainerBuilder $container
     * @throws Exception\NotFoundException
     */
    public function decorateContainer(ContainerBuilder $container)
    {
        if ($this->bundles_initialized == false) {
            $this->initBundles();
        }

        foreach ($this->bundle_configs as $bundle_config) {
            if ($bundle_config->services && !empty($bundle_config->services) && $bundle_config->ignore_services !== true) {
                foreach ($bundle_config->services as $service_class) {
                    $fq_class = $bundle_config->namespace.'\\Services\\'.$service_class;
                    if (class_exists($fq_class)) {
                        $service = new $fq_class();
                        $service->getServices(array(), $container);
                        $container->addObjectResource($fq_class);
                    }
                    else {
                        throw new NotFoundException(sprintf("Service class %s not found", $fq_class));
                    }
                }
            }

            $container->addResource(new FileResource($bundle_config->directory.'/config/bundle.yml'));
        }
    }

    public function setConfigCache(ConfigCache $config_cache)
    {
        $this->config_cache = $config_cache;
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
        if (!file_exists($this->config_dir.'/bundles.yml')) {
            throw new NotFoundException(sprintf("Bundles config could not be found: %", $this->config_dir));
        }

        if ($this->debug) {
            $filename_append = '_dev';

            if (file_exists($this->cache_dir.'/bundle_config.php')) {
                unlink($this->cache_dir.'/bundle_config.php');
            }
        }
        else {
            $filename_append = '';
        }

        $cache_path = $this->cache_dir.'/bundle_config'.$filename_append.'.php';

        if (!$this->config_cache) {
            $this->config_cache = new ConfigCache($cache_path, $this->debug);
        }

        $bundles_config_array = array();

        $this->cache_fresh = $this->config_cache->isFresh();

        if ((!$this->cache_fresh) || $force_cache_refresh === true) {

            $app_bundles_config = Yaml::parse($this->config_dir.'/bundles.yml');

            if ($this->debug) {
                if (file_exists($this->config_dir.'/bundles_dev.yml')) {
                    $app_bundles_config = array_replace_recursive($app_bundles_config, Yaml::parse($this->config_dir.'/bundles_dev.yml'));
                }
            }

            $resources = array(new FileResource($this->config_dir.'/bundles.yml'), new FileResource($this->config_dir.'/bundles_dev.yml'));

            foreach ($app_bundles_config as $bundle_name => $app_bundle_config) {
                if ($app_bundle_config['enabled'] == true) {
                    $bundle_location = $this->findBundleDirectory($bundle_name);
                    $bundle_config_location = $bundle_location.'/config/bundle.yml';

                    $resources[] = new FileResource($bundle_config_location);

                    $bundle_config = Yaml::parse(file_get_contents($bundle_config_location));
                    $bundle_config['enabled'] = true;
                    $bundle_config['directory'] = $bundle_location;

                    $app_bundle_config_overrides = array();
                    if (isset($app_bundle_config['config'])) {
                        $app_bundle_config_overrides = $app_bundle_config['config'];
                    }

                    // todo: parent bundle outside of BundleConfig for caching
                    if (isset($bundle_config['parent_bundle'])) {

                    }

                    $merged_config = array_replace_recursive($bundle_config, $app_bundle_config_overrides);

                    $configs = array($merged_config);

                    $processor = new Processor();

                    try {
                        $final_bundle_config = $processor->processConfiguration($this->config_validator, $configs);
                    }
                    catch (\Exception $e) {
                        throw new \Exception("Config for bundle {$bundle_config['name']} not configured correctly: ".$e->getMessage());
                    }

                    $bundles_config_array[$bundle_name] = $final_bundle_config;
                }
            }

            $this->config_cache->write('<?php return '.var_export($bundles_config_array, true).';', $resources);
        }
        else {
            $bundles_config_array = require $cache_path;
        }

        return $bundles_config_array;
    }

    /**
     * Finds a bundle by searching the bundle locations
     *
     * @param $bundle_name
     * @throws Exception\NotFoundException
     * @return string
     */
    public function findBundleDirectory($bundle_name)
    {
        foreach ($this->bundle_locations as $location) {
            $config_location = $location.'/'.$bundle_name.'/config/bundle.yml';

            if (file_exists($config_location)) {
                return $location.'/'.$bundle_name;
            }
        }

        throw new NotFoundException(sprintf("Bundle directory not found for bundle %s", $bundle_name));
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

        $config_location = $directory.'/config/bundle.yml';

        $config = Yaml::parse($config_location);
        $config['directory'] = $directory;
        $bundle_config = new BundleConfig($this, $config);

        //$this->bundle_configs[$bundle] = $bundle_config;

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
        if (isset($this->bundle_configs[$bundle])) {
            return $this->bundle_configs[$bundle];
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
        return $this->bundle_configs;
    }

    /**
     * @param $bundle_name
     * @return bool
     */
    public function hasBundle($bundle_name)
    {
        return isset($this->bundle_configs[$bundle_name]);
    }

    /**
     * @return bool
     */
    public function bundlesInitialized()
    {
        return $this->bundles_initialized;
    }

    /**
     * @return array
     */
    public function getBundleLocations()
    {
        return $this->bundle_locations;
    }

    /**
     * @return bool
     */
    public function cacheIsFresh()
    {
        return $this->cache_fresh;
    }

    /**
     * @param RouteCollection $routes
     */
    public function getBundleRoutes(RouteCollection $routes)
    {
        foreach ($this->bundle_configs as $bundle_config) {
            if (file_exists($bundle_config->directory . '/config/routes.yml') && $bundle_config->ignore_routes !== true) {
                $locator = new FileLocator(array($bundle_config->directory . '/config'));
                $loader = new YamlFileLoader($locator);
                $collection = $loader->load('routes.yml');

                $collection->addDefaults(array('_bundle' => $bundle_config->name));

                $routes->addCollection($collection);
            }
        }
    }
}