<?php
/**
 * User: Andy
 * Date: 11/08/2014
 * Time: 16:58
 */

namespace AVCMS\Core\Bundle;

use AVCMS\Core\Bundle\Exception\NotFoundException;
use AVCMS\Core\SettingsManager\SettingsManager;

class ResourceLocator
{
    protected $appDir = 'app';

    protected $templateDir;

    public function __construct(BundleManagerInterface $bundleManager, SettingsManager $settingsManager, $appDir = 'app') {
        $this->bundleManager = $bundleManager;
        $this->appDir = $appDir;
        $this->templateDir = $settingsManager->getSetting('template');
    }

    public function findFileDirectory($bundleName, $file, $type)
    {
        $bundleConfig = $this->bundleManager->getBundleConfig($bundleName);

        foreach ($this->getResourceDirs($bundleConfig, $type) as $dir) {
            $dir = $dir.'/'.$file;

            if (file_exists($dir)) {
                return $dir;
            }
        }

        throw new NotFoundException(sprintf('File %s not found in bundle %s', $file, $bundleConfig->name));
    }

    private function getResourceDirs($bundleConfig, $resourceType)
    {
        $dirs = array(
            $this->templateDir.'/'.$resourceType.'/'.$bundleConfig->name,
            $this->templateDir.'/'.$bundleConfig->name,
            $this->appDir.'/resources/'.$bundleConfig->name,
            $bundleConfig->directory.'/resources/'.$resourceType,
        );

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