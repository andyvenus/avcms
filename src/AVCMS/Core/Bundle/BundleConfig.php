<?php
/**
 * User: Andy
 * Date: 14/07/2014
 * Time: 12:59
 */

namespace AVCMS\Core\Bundle;

class BundleConfig
{
    protected $route;

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
            $config = array_merge($config, $app_config);
        }

        $this->config = $config;
    }

    public function __get($name)
    {
        if (!isset($this->config[$name])) {
            return null;
        }
        if (is_array($this->config[$name])) {
            return $this->arrayToObject($this->config[$name]);
        }

        return $this->config[$name];
    }

    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    public function getConfigArray()
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
}