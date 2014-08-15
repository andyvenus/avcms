<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:27
 */

namespace AVCMS\Core\Bundle;

use AVCMS\Core\Bundle\Config\BundleConfigurationValidator;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class BundleManager {

    protected $bundles;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

    protected $bundles_initialized = false;

    protected $bundle_locations = array('src/AVCMS/Bundles', 'src/AVCMS/BundlesDev');

    protected $bundle_configs;

    protected $routes;

    protected $cache_fresh = true;

    protected $config_validator;

    //todo: add bundle_locations to construct
    public function __construct($debug = false, ConfigurationInterface $config_validator = null)
    {
        $this->resource_locator = new BundleResourceLocator(null, null);

        if ($config_validator == null) {
            $config_validator = new BundleConfigurationValidator();
        }

        $this->config_validator = $config_validator;

        $this->debug = $debug;

        $this->initBundles();
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
     */
    public function decorateContainer(ContainerBuilder $container)
    {
        if ($this->bundles_initialized == false) {
            $this->initBundles();
        }

        $twig_container = $container->getDefinition('twig.filesystem');

        foreach ($this->bundle_configs as $bundle_name => $bundle_config) {
            # Services
            if ($bundle_config->services && !empty($bundle_config->services) && $bundle_config->ignore_services !== true) {
                foreach ($bundle_config->services as $service_class) {
                    $fq_class = $bundle_config->namespace.'\\Services\\'.$service_class;
                    $service = new $fq_class();
                    $service->getServices(array(), $container);
                    $container->addObjectResource($fq_class);
                }
            }

            # Templates folder
            if (file_exists($bundle_config->directory.'/templates')) {
                $twig_container->addMethodCall('addPath', array($bundle_config->directory.'/templates', $bundle_config->name));
            }
        }
    }

    /**
     * Load all bundle configuration files (config/bundle.yml)
     * from yaml or from config cache.
     *
     * @return array|mixed
     */
    public function loadAppBundleConfig()
    {
        if ($this->debug) {
            $filename_append = '_dev';

            if (file_exists('cache/bundle_config.php')) {
                unlink('cache/bundle_config.php');
            }
        }
        else {
            $filename_append = '';
        }

        $cache_path = 'cache/bundle_config'.$filename_append.'.php';

        $bundle_config_cache = new ConfigCache($cache_path, $this->debug);
        $bundles_config_array = array();

        if (!$bundle_config_cache->isFresh()) {
            $this->cache_fresh = false;
            $app_bundles_config = Yaml::parse('app/config/bundles.yml');

            if ($this->debug) {
                $app_bundles_config = array_replace_recursive($app_bundles_config, Yaml::parse('app/config/bundles_dev.yml'));
            }

            $resources = array(new FileResource('app/config/bundles.yml'), new FileResource('app/config/bundles_dev.yml'));

            foreach ($app_bundles_config as $bundle_name => $app_bundle_config) {
                if ($app_bundle_config['enabled'] == true) {
                    $bundle_location = $this->findBundleDirectory($bundle_name);
                    $bundle_config_location = $bundle_location.'/config/bundle.yml';

                    $resources[] = new FileResource($bundle_config_location);

                    $bundle_config = Yaml::parse(file_get_contents($bundle_config_location));
                    $bundle_config['enabled'] = true;
                    $bundle_config['directory'] = $bundle_location;

                    if (isset($app_bundle_config['config'])) {
                        $app_bundle_config_overrides = $app_bundle_config['config'];
                    }
                    else {
                        $app_bundle_config_overrides = array();
                    }

                    // todo: parent bundle outside of BundleConfig for caching
                    if (isset($bundle_config['parent_bundle'])) {

                    }

                    $merged_config = array_replace_recursive($bundle_config, $app_bundle_config_overrides);

                    $configs = array($merged_config);

                    $processor = new Processor();
                    $final_bundle_config = $processor->processConfiguration($this->config_validator, $configs);

                    $bundles_config_array[$bundle_name] = $final_bundle_config;
                }
            }

            $bundle_config_cache->write('<?php return '.var_export($bundles_config_array, true).';', $resources);
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
    }

    /**
     * Explicitly load a bundle's bundle.yml file, no cache
     *
     * @param $bundle
     * @return BundleConfig|null
     * @throws \Exception
     */
    public function loadBundleConfig($bundle)
    {
        $directory = $this->findBundleDirectory($bundle);

        $config_location = $directory.'/config/bundle.yml';


        if (!isset($config_location)) {
            throw new \Exception(sprintf('Cannot find bundle %s or the bundle\'s configuration file /config/bundle.yml does not exist', $bundle));
        }

        if (isset($config_location)) {
            $config = Yaml::parse($config_location);
            $config['directory'] = $directory;
            return new BundleConfig($this, $config);
        }
        else {
            return null;
        }
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
     * Finds a bundle resource by hierarchy:
     *      Template
     *      App
     *      Bundle
     *      Bundle Parent
     *
     * @param $bundle_name
     * @param $file
     * @param $type
     * @return string
     */
    public function getBundleResource($bundle_name, $file, $type)
    {
        $config = $this->getBundleConfig($bundle_name);

        return $this->resource_locator->findFileDirectory($config, $file, $type);
    }

    /**
     * @return bool
     */
    public function cacheIsFresh()
    {
        return $this->cache_fresh;
    }

    public function onKernelBoot(ContainerInterface $container)
    {
        // Update User Settings if the cache was not fresh on request
        if ($this->cache_fresh === false) {
            $settings_manager = $container->get('settings_manager');

            foreach ($this->bundle_configs as $bundle_config) {
                if (isset($bundle_config['user_settings']) && !empty($bundle_config['user_settings'])) {

                    $bundle_settings = array();
                    foreach ($bundle_config['user_settings'] as $setting_name => $setting) {
                        $bundle_settings[$setting_name] = (isset($setting['default']) ? $setting['default'] : '');
                    }

                    $settings_manager->addSettings($bundle_settings);
                }
            }
        }

        $routes = $container->get('routes');

        foreach ($this->bundle_configs as $bundle_config) {
            # Routes
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