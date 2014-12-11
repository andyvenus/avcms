<?php
/**
 * User: Andy
 * Date: 11/08/2014
 * Time: 16:58
 */

namespace AVCMS\Core\Bundle;

use AV\Kernel\Bundle\BundleConfig;
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

    protected function getResourceDirs(BundleConfig $bundleConfig, $resourceType, $originalOnly)
    {
        $avcmsDirs = [];
        if ($originalOnly === false) {
            $avcmsDirs[] = $this->rootDir . '/webmaster/resources/' . $bundleConfig->name . '/' . $resourceType;
            $avcmsDirs[] = $this->templateDir . '/' . $resourceType . '/' . $bundleConfig->name;
            $avcmsDirs[] = $this->templateDir . '/' . $bundleConfig->name;
        }

        $dirs = parent::getResourceDirs($bundleConfig, $resourceType, $originalOnly);

        return (array_merge($avcmsDirs, $dirs));
    }
}