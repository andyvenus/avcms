<?php
/**
 * User: Andy
 * Date: 17/12/2013
 * Time: 21:25
 */

namespace AV\Bundles\Twig\TwigLoader;


use AV\Kernel\Bundle\ResourceLocator;
use Twig_Error_Loader;

class BundleTwigLoaderFilesystem extends \Twig_Loader_Filesystem
{
    public function __construct(ResourceLocator $resource_locator)
    {
        $this->resource_locator = $resource_locator;
    }

    protected function findTemplate($name)
    {
        $name = $this->normalizeName($name);

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $this->validateName($name);

        list($namespace, $shortname) = $this->parseName($name);

        if ($this->resource_locator->bundleExists($namespace)) {
            return $this->resource_locator->findFileDirectory($namespace, $shortname, 'templates');
        }

        if (!isset($this->paths[$namespace])) {
            throw new Twig_Error_Loader(sprintf('There are no registered paths for namespace "%s".', $namespace));
        }

        foreach ($this->paths[$namespace] as $path) {
            if (is_file($path.'/'.$shortname)) {
                return $this->cache[$name] = $path.'/'.$shortname;
            }
        }

        throw new Twig_Error_Loader(sprintf('Unable to find template "%s" (looked into: %s).', $name, implode(', ', $this->paths[$namespace])));
    }
} 