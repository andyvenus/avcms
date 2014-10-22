<?php
/**
 * User: Andy
 * Date: 11/08/2014
 * Time: 16:58
 */

namespace AVCMS\Core\Bundle;

use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Core\SettingsManager\SettingsManager;
use AV\Kernel\Bundle\ResourceLocator as BaseResourceLocator;

class ResourceLocator extends BaseResourceLocator
{
    protected $templateDir;

    public function __construct(BundleManagerInterface $bundleManager, SettingsManager $settingsManager, $appDir = 'app') {
        $this->templateDir = $settingsManager->getSetting('template');
        parent::__construct($bundleManager, $appDir);
    }

    protected function getResourceDirs($bundleConfig, $resourceType)
    {
        $templateDirs = array(
            $this->templateDir.'/'.$resourceType.'/'.$bundleConfig->name,
            $this->templateDir.'/'.$bundleConfig->name,
        );

        $dirs = parent::getResourceDirs($bundleConfig, $resourceType);

        return (array_merge($templateDirs, $dirs));
    }
}