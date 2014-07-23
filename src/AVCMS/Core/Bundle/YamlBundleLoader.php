<?php
/**
 * User: Andy
 * Date: 14/07/2014
 * Time: 12:55
 */

namespace AVCMS\Core\Bundle;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlBundleLoader extends FileLoader
{
    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string $type The resource type
     * @return \AVCMS\Core\Bundle\BundleConfig
     */
    public function load($resource, $type = null)
    {
        $config = Yaml::parse($resource);

        return new BundleConfig($config);
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string $type The resource type
     *
     * @return bool    true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}