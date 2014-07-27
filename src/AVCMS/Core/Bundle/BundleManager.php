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

    protected $bundle_instances = array();

    protected $container;

    protected $bundles_initialized = false;

    protected $bundle_namespaces;

    protected $bundle_locations = array('src/AVCMS/Bundles', 'src/AVBlog/Bundles');

    protected $bundle_configs;

    protected $routes;

    public function __construct(array $bundles, array $bundle_namespaces, ContainerBuilder $container)
    {
        $this->container = $container;
        $this->bundles = $bundles;
        $this->bundle_namespaces = $bundle_namespaces;

        $this->routes = $this->container->getParameter('routes');
    }

    /**
     *  Load up the bundles
     */
    public function initBundles()
    {
        foreach ($this->bundles as $bundle) {
            if ($bundle_config = $this->loadBundleConfig($bundle)) {
                $this->bundle_configs[$bundle] = $bundle_config;

                # Routes
                if (file_exists($bundle_config->directory . '/config/routes.yml')) {
                    $locator = new FileLocator(array($bundle_config->directory . '/config'));
                    $loader = new YamlFileLoader($locator);
                    $collection = $loader->load('routes.yml');

                    $collection->addDefaults(array('_bundle' => $bundle));

                    $this->routes->addCollection($collection);
                }

                if ($bundle_config->setup_class) {
                    if ($bundle_config->namespace && strpos($bundle_config->setup_class, $bundle_config->namespace) === false) {
                        $setup_class = $bundle_config->namespace.'\\'.$bundle_config->setup_class;
                    }
                    else {
                        $setup_class = $bundle_config->setup_class;
                    }

                    $bundle_setup = new $setup_class($bundle_config, $this->container);
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
            throw new \Exception(sprintf('Configuration file bundle.yml not found for the %s bundle', $bundle));
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
        return $this->bundle_configs[$bundle];
    }

    public function getBundles()
    {
        return $this->bundle_instances;
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
}