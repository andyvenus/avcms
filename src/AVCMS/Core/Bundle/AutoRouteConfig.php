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
    protected $route_names;

    public function __construct($route_names = array())
    {
        $this->route_names = $route_names;
    }

    public function __get($route_name)
    {
        if (isset($this->route_names[$route_name])) {
            return $this->route_names[$route_name];
        }
        else {
            return $route_name;
        }
    }

    public function __isset($route_name)
    {
        return true;
    }
} 