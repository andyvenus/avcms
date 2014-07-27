<?php
/**
 * User: Andy
 * Date: 14/07/2014
 * Time: 12:59
 */

namespace AVCMS\Core\Bundle;

class BundleConfig
{
    public function __construct(BundleManager $bundle_manager, $config)
    {
        if (isset($config['parent_bundle']))
        {
            $parent_config = $bundle_manager->loadBundleConfig($config['parent_bundle'])->getConfigArray();

            if (isset($parent_config['parent_bundle'])) {
                throw new \Exception(sprintf("Cannot extend the Bundle %s as it extends another bundle itself", $config['parent_bundle']));
            }

            $config = array_merge($parent_config, $config);

            $config['parent_config'] = $parent_config;
        }

        if ($app_config = $bundle_manager->getAppConfig($config['name'])) {
            $config = array_replace_recursive($config, $app_config);
        }

        $this->config_array = $config;
        $this->config = $this->arrayToObject($this->config_array);

        if (!isset($this->config->route)) {
            $this->config->route = $this->autoRouteConfig();
        }
        else {
            $this->config->route = $this->autoRouteConfig($this->config_array['route']);
        }
    }

    public function __get($name)
    {
        if (!isset($this->config->$name)) {
            return null;
        }

        return $this->config->$name;
    }

    public function __isset($name)
    {
        return isset($this->config->$name);
    }

    public function getConfigArray()
    {
        return $this->config_array;
    }

    public function getConfigObject()
    {
        return $this->config;
    }

    protected function arrayToObject($array)
    {
        if (is_array($array)) {
            return (object) array_map(array($this, 'arrayToObject'), $array);
        }
        else {
            return $array;
        }
    }

    protected function autoRouteConfig($route_names = array())
    {
        return new AutoRouteConfig($route_names);
    }

}