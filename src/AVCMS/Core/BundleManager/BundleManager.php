<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:27
 */

namespace AVCMS\Core\BundleManager;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class BundleManager {

    protected $bundles;

    protected $container;

    protected $bundles_initialized = false;

    public function __construct(array $bundles, ContainerBuilder $container)
    {
        $this->container = $container;
        $this->bundles = $bundles;
    }

    /**
     *  Load up the bundles
     */
    public function initBundles()
    {
        foreach ($this->bundles as $bundle_name => $bundle) {
            $full_namespace = '\\'.$bundle['namespace'].'\\'.$bundle_name.'Bundle';

            if (class_exists($full_namespace)) {
                /**
                 * @var $bundle_instance \AVCMS\Core\Bundle\BundleInterface
                 */
                $bundle_instance = new $full_namespace($this->container);

                $bundle_instance->setUp();
                $bundle_instance->modifyContainer($this->container);
                $bundle_instance->routes($this->container->getParameter('routes'));

                $this->bundles[$bundle_name]['instance'] = $bundle_instance;
            }
        }

        $this->bundles_initialized = true;
    }

    public function getBundles()
    {
        return $this->bundles;
    }

    public function hasBundle($bundle_name)
    {
        return isset($this->bundles[$bundle_name]);
    }

    public function bundlesInitialized()
    {
        return $this->bundles_initialized;
    }
}