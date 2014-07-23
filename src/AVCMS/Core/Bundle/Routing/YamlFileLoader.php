<?php
/**
 * User: Andy
 * Date: 14/07/2014
 * Time: 18:53
 */

namespace AVCMS\Core\Bundle\Routing;

use Symfony\Component\Routing\RouteCollection;

class YamlFileLoader extends \Symfony\Component\Routing\Loader\YamlFileLoader
{
    protected function parseRoute(RouteCollection $collection, $name, array $config, $path)
    {
        parent::parseRoute($collection, $name, $config, $path);
    }
} 