<?php
/**
 * User: Andy
 * Date: 21/08/2014
 * Time: 11:19
 */

namespace AVCMS\Core\Kernel;

use AVCMS\Core\Bundle\BundleManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router as BaseRouter;

class Router extends BaseRouter
{
    /**
     * @var \AVCMS\Core\Bundle\BundleManagerInterface
     */
    protected $bundle_manager;

    public function __construct(LoaderInterface $loader, $resource, array $options = array(), BundleManagerInterface $bundle_manager, RequestContext $context = null, LoggerInterface $logger = null)
    {
        $this->bundle_manager = $bundle_manager;
        parent::__construct($loader, $resource, $options, $context, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->loader->load($this->resource, $this->options['resource_type']);

            $this->bundle_manager->getBundleRoutes($this->collection);
        }

        return $this->collection;
    }
} 