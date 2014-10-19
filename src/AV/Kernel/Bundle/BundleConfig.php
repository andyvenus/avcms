<?php
/**
 * User: Andy
 * Date: 14/07/2014
 * Time: 12:59
 */

namespace AV\Kernel\Bundle;

use AVCMS\Core\Config\Config;

class BundleConfig extends Config
{
    public function __construct(BundleManagerInterface $bundleManager, $config)
    {
        if (isset($config['parent_bundle'])) {
            $parentConfig = $bundleManager->loadBundleConfig($config['parent_bundle'])->getConfigArray();

            if (isset($parentConfig['parent_bundle'])) {
                throw new \Exception(sprintf("Cannot extend the Bundle %s as it extends another bundle itself", $config['parent_bundle']));
            }

            $config = array_replace_recursive($parentConfig, $config);

            $config['parent_config'] = $parentConfig;
        }

        $this->configArray = $config;
        $this->config = $this->arrayToObject($this->configArray);
    }

}