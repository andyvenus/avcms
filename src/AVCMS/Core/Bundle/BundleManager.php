<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:27
 */

namespace AVCMS\Core\Bundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class BundleManager {

    protected $bundles;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected $container;

    protected $bundles_initialized = false;

    protected $bundle_namespaces;

    protected $bundle_locations = array('src/AVCMS/Bundles', 'src/AVCMS/BundlesDev');

    protected $bundle_configs;

    protected $routes;

    public function __construct(array $bundles, array $bundle_namespaces, ContainerBuilder $container)
    {
        $this->container = $container;
        $this->bundles = $bundles;
        $this->bundle_namespaces = $bundle_namespaces;

        $this->routes = $this->container->getParameter('routes');

        $this->resource_locator = new BundleResourceLocator(null, null);
    }

    /**
     *  Load up the bundles
     */
    public function initBundles()
    {
        $twig_container = $this->container->getDefinition('twig.filesystem');

        foreach ($this->bundles as $bundle_name => $bundle) {
            if (isset($bundle['enabled']) && $bundle['enabled'] === true && $bundle_config = $this->loadBundleConfig($bundle_name)) {
                $this->bundle_configs[$bundle_name] = $bundle_config;

                # Routes
                if (file_exists($bundle_config->directory . '/config/routes.yml')) {
                    $locator = new FileLocator(array($bundle_config->directory . '/config'));
                    $loader = new YamlFileLoader($locator);
                    $collection = $loader->load('routes.yml');

                    $collection->addDefaults(array('_bundle' => $bundle_name));

                    $this->routes->addCollection($collection);
                }

                # Set-up class
                if ($bundle_config->setup_class) {
                    if ($bundle_config->namespace && strpos($bundle_config->setup_class, $bundle_config->namespace) === false) {
                        $setup_class = $bundle_config->namespace.'\\'.$bundle_config->setup_class;
                    }
                    else {
                        $setup_class = $bundle_config->setup_class;
                    }

                    $bundle_setup = new $setup_class($bundle_config, $this->container);
                }

                # Services
                if ($bundle_config->services && !empty($bundle_config->services)) {
                    foreach ($bundle_config->services as $service_class) {
                        $fq_class = $bundle_config->namespace.'\\Services\\'.$service_class;
                        $service = new $fq_class();
                        $service->getServices(array(), $this->container);
                    }
                }

                # Templates folder
                if (file_exists($bundle_config->directory.'/templates')) {
                    $twig_container->addMethodCall('addPath', array($bundle_config->directory.'/templates', $bundle_config->name));
                }
            }
        }

        $this->bundles_initialized = true;
    }

    public function loadBundleConfig($bundle)
    {
        foreach ($this->bundle_locations as $location) {
            $config_location = $location.'/'.$bundle.'/config/bundle.yml';
            if (file_exists($config_location)) {
                $bundle_location = $location.'/'.$bundle;
                break;
            }
        }

        if (!isset($bundle_location)) {
            throw new \Exception(sprintf('Cannot find bundle %s or the bundle\'s configuration file /config/bundle.yml does not exist', $bundle));
        }

        if (isset($config_location)) {
            $config = Yaml::parse($config_location);
            $config['directory'] = $bundle_location;
            return new BundleConfig($this, $config);
        }
        else {
            return null;
        }
    }

    public function getAppConfig($bundle)
    {
        return array(
            'model' => array(
                'users2' => 'Fak'
            )
        );
    }

    public function getBundleConfig($bundle)
    {
        if (isset($this->bundle_configs[$bundle])) {
            return $this->bundle_configs[$bundle];
        }
        else {
            return $this->loadBundleConfig($bundle);
        }
    }

    public function getBundleConfigs()
    {
        return $this->bundle_configs;
    }

    public function hasBundle($bundle_name)
    {
        return isset($this->bundle_configs[$bundle_name]);
    }

    public function bundlesInitialized()
    {
        return $this->bundles_initialized;
    }

    public function setBundleNamespace($namespace)
    {
        $this->bundle_namespaces[] = $namespace;
    }

    public function getBundleLocations()
    {
        return $this->bundle_locations;
    }

    public function getBundleResource($bundle_name, $file, $type)
    {
        $config = $this->getBundleConfig($bundle_name);

        return $this->resource_locator->findFileDirectory($config, $file, $type);
    }
}