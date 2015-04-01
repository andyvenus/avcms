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
            parent::getRouteCollection();

            if (file_exists($this->rootDir.'/webmaster/config/routes.yml')) {
                $webmasterRoutes = $this->loader->load($this->rootDir.'/webmaster/config/routes.yml', $this->options['resource_type']);
                $this->collection->addCollection($webmasterRoutes);
            }
        }

        return $this->collection;
    }
} 
