<?php
/**
 * User: Andy
 * Date: 14/07/2014
 * Time: 12:59
 */

namespace AVCMS\Core\Bundle;

use AVCMS\Core\Config\Config;

class BundleConfig extends Config
{
    public function __construct(BundleManager $bundle_manager, $config)
    {
        if (isset($config['parent_bundle']))
        {
            $parent_config = $bundle_manager->loadBundleConfig($config['parent_bundle'])->getConfigArray();

            if (isset($parent_config['parent_bundle'])) {
                throw new \Exception(sprintf("Cannot extend the Bundle %s as it extends another bundle itself", $config['parent_bundle']));
            }

            $config = array_replace_recursive($parent_config, $config);

            $config['parent_config'] = $parent_config;
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

    protected function autoRouteConfig($route_names = array())
    {
        return new AutoRouteConfig($route_names);
    }
}