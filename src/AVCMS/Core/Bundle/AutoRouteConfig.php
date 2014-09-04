<?php
/**
 * User: Andy
 * Date: 25/07/2014
 * Time: 10:39
 */

namespace AVCMS\Core\Bundle;

/**
 * Holds route names config. If a route name has not got a set replacement,
 * the route name is simply returned as the value
 *
 * Class AutoRouteConfig
 * @package AVCMS\Core\Bundle
 */
class AutoRouteConfig
{
    protected $routeNames;

    public function __construct($route_names = array())
    {
        $this->routeNames = $route_names;
    }

    public function __get($routeName)
    {
        if (isset($this->routeNames[$routeName])) {
            return $this->routeNames[$routeName];
        }
        else {
            return $routeName;
        }
    }

    public function __isset($routeName)
    {
        return true;
    }
} 