<?php
/**
 * User: Andy
 * Date: 21/08/2014
 * Time: 11:19
 */

namespace AV\Kernel;

use AV\Kernel\Bundle\BundleManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router as BaseRouter;

class Router extends BaseRouter
{
    /**
     * @var \AV\Kernel\Bundle\BundleManagerInterface
     */
    protected $bundleManager;

    public function __construct(LoaderInterface $loader, $resource, array $options = array(), BundleManagerInterface $bundle_manager = null, RequestContext $context = null, LoggerInterface $logger = null)
    {
        $this->bundleManager = $bundle_manager;
        parent::__construct($loader, $resource, $options, $context, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->loader->load($this->resource, $this->options['resource_type']);

            if ($this->bundleManager) {
                $this->bundleManager->getBundleRoutes($this->collection);
            }

            $this->collection->addResource(new FileResource('app/config/bundles.yml'));
            $this->collection->addResource(new FileResource('app/config/bundles_dev.yml'));
        }

        return $this->collection;
    }
}