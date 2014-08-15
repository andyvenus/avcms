<?php
/**
 * User: Andy
 * Date: 14/08/2014
 * Time: 12:05
 */

namespace AVCMS\Core\Bundle\Config;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlBundleLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        $config_values = Yaml::parse($resource);
    }

    public function supports($resource, $type = null)
    {
        // TODO: Implement supports() method.
    }
}