<?php
/**
 * User: Andy
 * Date: 11/08/2014
 * Time: 16:58
 */

namespace AV\Kernel\Bundle;

use AV\Kernel\Bundle\Exception\NotFoundException;

class ResourceLocator
{
    protected $appDir;

    protected $rootDir;

    protected $bundleManager;

    public function __construct(BundleManagerInterface $bundleManager, $rootDir, $appDir) {
        $this->bundleManager = $bundleManager;
        $this->rootDir = $rootDir;
        $this->appDir = $appDir;
    }

    /**
     * @param $bundleName
     * @param $file
     * @param $type
     * @param bool $originalOnly
     * @throws NotFoundException
     * @return string
     */
    public function findFileDirectory($bundleName, $file, $type, $originalOnly = false)
    {
        $bundleConfig = $this->bundleManager->getBundleConfig($bundleName);

        foreach ($this->getResourceDirs($bundleConfig, $type, $originalOnly) as $dir) {
            $dir = $dir.'/'.$file;

            if (file_exists($dir)) {
                return $dir;
            }
        }

        throw new NotFoundException(sprintf('File %s not found in bundle %s', $file, $bundleConfig->name));
    }

    public function findFileHierarchy($bundleName, $file, $type)
    {
        $bundleConfig = $this->bundleManager->getBundleConfig($bundleName);

        $fileLocations = $this->getResourceDirs($bundleConfig, $type, false);

        foreach ($fileLocations as $type => $dir) {
            $dir = $dir.'/'.$file;

            if (!file_exists($dir)) {
                unset($fileLocations[$type]);
            }
        }

        return $fileLocations;
    }

    public function getResourceDirs(BundleConfig $bundleConfig, $resourceType, $originalOnly)
    {
        $dirs = [];

        if ($originalOnly === false) {
            $dirs['app'] = $this->appDir . '/resources/' . $bundleConfig->name;
        }

        $dirs['bundle'] = $this->rootDir.'/'.$bundleConfig->directory.'/resources/'.$resourceType;

        if (isset($bundleConfig->parent_config)) {
            $dirs['bundle_parent'] = $this->rootDir.'/'.$bundleConfig->parent_config->directory.'/resources/'.$resourceType;
        }

        return $dirs;
    }

    public function bundleExists($bundle)
    {
        return $this->bundleManager->hasBundle($bundle);
    }
}
