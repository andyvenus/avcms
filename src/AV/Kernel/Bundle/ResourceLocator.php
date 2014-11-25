<?php
/**
 * User: Andy
 * Date: 11/08/2014
 * Time: 16:58
 */

namespace AV\Kernel\Bundle;

use AV\Kernel\Bundle\Exception\NotFoundException;
use AVCMS\Core\SettingsManager\SettingsManager;

class ResourceLocator
{
    protected $appDir = 'app';

    public function __construct(BundleManagerInterface $bundleManager, $rootDir, $appDir) {
        $this->bundleManager = $bundleManager;
        $this->rootDir = $rootDir;
        $this->appDir = $appDir;
    }

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

    protected function getResourceDirs($bundleConfig, $resourceType, $originalOnly)
    {
        $dirs = [];

        if ($originalOnly === false) {
            $dirs[] = $this->appDir . '/resources/' . $bundleConfig->name;
        }

        $dirs[] = $this->rootDir.'/'.$bundleConfig->directory.'/resources/'.$resourceType;

        if (isset($bundleConfig->parent_config)) {
            $dirs[] = $bundleConfig->parent_config->directory.'/resources/'.$resourceType;
        }

        return $dirs;
    }

    public function bundleExists($bundle)
    {
        return $this->bundleManager->hasBundle($bundle);
    }
}