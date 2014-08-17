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
    protected $app_dir = 'app';

    protected $template_dir;

    public function __construct(BundleManager $bundle_manager, SettingsManager $settings_manager, $app_dir = 'app') {
        $this->bundle_manager = $bundle_manager;
        $this->app_dir = $app_dir;
        $this->template_dir = $settings_manager->getSetting('template');
    }

    public function findFileDirectory($bundle_name, $file, $type)
    {
        $bundle_config = $this->bundle_manager->getBundleConfig($bundle_name);

        foreach ($this->getResourceDirs($bundle_config, $type) as $dir) {
            $dir = $dir.'/'.$file;

            if (file_exists($dir)) {
                return $dir;
            }
        }

        throw new NotFoundException(sprintf('File %s not found in bundle %s', $file, $bundle_config->name));
    }

    private function getResourceDirs($bundle_config, $resource_type)
    {
        $dirs = array(
            $this->template_dir.'/'.$bundle_config->name,
            $this->app_dir.'/bundles/'.$bundle_config->name,
            $bundle_config->directory.'/resources/'.$resource_type,
        );

        if (isset($bundle_config->parent_config)) {
            $dirs[] = $bundle_config->parent_config->directory.'/resources/'.$resource_type;
        }

        return $dirs;
    }

    public function bundleExists($bundle)
    {
        return $this->bundle_manager->hasBundle($bundle);
    }
}