<?php
/**
 * User: Andy
 * Date: 10/12/14
 * Time: 15:29
 */

namespace AVCMS\Core\Kernel;

use AV\Kernel\Bundle\BundleManagerInterface;
use AV\Kernel\Router as BaseRouter;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\RequestContext;

class Router extends BaseRouter
{
    protected $rootDir;

    public function __construct(LoaderInterface $loader, $resource, $appDir, $rootDir, array $options = array(), BundleManagerInterface $bundleManager = null, RequestContext $context = null, LoggerInterface $logger = null)
    {
        $this->rootDir = $rootDir;
        parent::__construct($loader, $resource, $appDir, $options, $bundleManager, $context, $logger);
    }

    public function getRouteCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->loader->load($this->resource, $this->options['resource_type']);

            if (file_exists($this->rootDir.'/webmaster/config/route_overrides.yml')) {
                $webmasterOverrideRoutes = $this->loader->load($this->rootDir.'/webmaster/config/route_overrides.yml', $this->options['resource_type']);
                $this->collection->addCollection($webmasterOverrideRoutes);
            }

            if ($this->bundleManager) {
                $this->bundleManager->getBundleRoutes($this->collection);
            }

            $this->collection->addResource(new FileResource($this->appDir.'/config/bundles.yml'));
            $this->collection->addResource(new FileResource($this->appDir.'/config/bundles_dev.yml'));

            if (file_exists($this->rootDir.'/webmaster/config/routes.yml')) {
                $webmasterRoutes = $this->loader->load($this->rootDir.'/webmaster/config/routes.yml', $this->options['resource_type']);
                $this->collection->addCollection($webmasterRoutes);
            }
        }

        return $this->collection;
    }
} 
