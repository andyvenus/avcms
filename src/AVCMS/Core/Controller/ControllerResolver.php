<?php

namespace AVCMS\Core\Controller;

use AV\Kernel\Bundle\BundleManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ControllerResolver extends BaseControllerResolver
{

    public function __construct(ContainerInterface $container, BundleManagerInterface $bundleManager = null, LoggerInterface $logger = null)
    {
        $this->container = $container;
        $this->bundleManager = $bundleManager;

        parent::__construct($logger);
    }

    protected function createController($controller)
    {
        if (1 === $length = substr_count($controller, '::')) {
            list($class, $method) = explode('::', $controller, 2);
        }
        elseif ($length === 2 && $this->bundleManager !== null) {
            list($bundle, $class, $method) = explode('::', $controller, 3);

            if (!$this->bundleManager->hasBundle($bundle)) {
                throw new \Exception(sprintf("Cannot build controller %s - Bundle %s not initialised", $controller, $bundle));
            }

            $bundleConfig = $this->bundleManager->getBundleConfig($bundle);
            $namespace = $bundleConfig->namespace;
            $class = $namespace.'\Controller\\'.$class;
        }
        else {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = new $class();
        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        if (isset($bundleConfig) && is_callable(array($controller, 'setBundle'))) {
            $controller->setBundle($bundleConfig);
        }

        return array($controller, $method);
    }

    public function getArguments(Request $request, $controller)
    {
        if (is_callable(array($controller, 'setUp'))) {
            $controller->setUp($request);
        }
        elseif (is_array($controller) && is_callable(array($controller[0], 'setUp'))) {
            $controller[0]->setUp($request);
        }

        return parent::getArguments($request, $controller);
    }
}