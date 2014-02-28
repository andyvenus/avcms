<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 11:27
 */

namespace AVCMS\Core\BundleManager;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class BundleInitializer {

    protected $bundles;

    protected $container;

    public function __construct(array $bundles, ContainerBuilder $container)
    {
        $this->container = $container;
        $this->bundles = $bundles;
    }

    /**
     */
    public function initBundles()
    {
        foreach ($this->bundles as $bundle_name => $bundle_obj_ref) {
            $full_namespace = '\\'.$bundle_obj_ref.'\\'.$bundle_name.'Bundle';

            if (class_exists($full_namespace)) {
                /**
                 * @var $bundle_obj \AVCMS\Core\Bundle\BundleInterface
                 */
                $bundle_obj = new $full_namespace($this->container);

                $bundle_obj->setUp();
                $bundle_obj->modifyContainer($this->container);
                $bundle_obj->routes($this->container->getParameter('routes'));
            }
        }
    }
}