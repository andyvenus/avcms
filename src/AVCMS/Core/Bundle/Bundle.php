<?php
/**
 * User: Andy
 * Date: 05/02/2014
 * Time: 13:34
 */

namespace AVCMS\Core\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollection;

abstract class Bundle implements BundleInterface {
    public function modifyContainer(ContainerBuilder $container)
    {

    }

    public function routes(RouteCollection $routes)
    {

    }

    public function bundleInfo()
    {

    }
} 