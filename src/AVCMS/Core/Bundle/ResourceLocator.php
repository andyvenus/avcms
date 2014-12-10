<?php
/**
 * User: Andy
 * Date: 11/08/2014
 * Time: 16:58
 */

namespace AVCMS\Core\Bundle;

use AV\Kernel\Bundle\BundleManagerInterface;
use AV\Kernel\Bundle\ResourceLocator as BaseResourceLocator;
use AVCMS\Core\SettingsManager\SettingsManager;

class ResourceLocator extends BaseResourceLocator
{
    protected $templateDir;

    public function __construct(BundleManagerInterface $bundleManager, SettingsManager $settingsManager, $rootDir, $appDir) {
        $this->templateDir = $settingsManager->getSetting('template');
        parent::__construct($bundleManager, $rootDir, $appDir);
    }

    protected function getResourceDirs($bundleConfig, $resourceType, $originalOnly)
    {
        $templateDirs = [];
        if ($originalOnly === false) {
            $templateDirs = array(
                $this->templateDir . '/' . $resourceType . '/' . $bundleConfig->name,
                $this->templateDir . '/' . $bundleConfig->name,
            );
        }

        $dirs = parent::getResourceDirs($bundleConfig, $resourceType, $originalOnly);

        return (array_merge($templateDirs, $dirs));
    }
}