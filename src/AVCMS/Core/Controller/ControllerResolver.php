<?php

namespace AVCMS\Core\Controller;

use AVCMS\Core\Bundle\BundleManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ControllerResolver extends BaseControllerResolver
{

    public function __construct(ContainerInterface $container, BundleManagerInterface $bundle_manager, LoggerInterface $logger = null)
    {
        $this->container = $container;
        $this->bundle_manager = $bundle_manager;

        parent::__construct($logger);
    }

    protected function createController($controller)
    {
        if (1 === $length = substr_count($controller, '::')) {
            list($class, $method) = explode('::', $controller, 2);
        }
        elseif ($length === 2) {
            list($bundle, $class, $method) = explode('::', $controller, 3);

            if (!$this->bundle_manager->hasBundle($bundle)) {
                throw new \Exception(sprintf("Cannot build controller %s - Bundle %s not initialised", $controller, $bundle));
            }

            $namespace = $this->bundle_manager->getBundleConfig($bundle)->namespace;
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