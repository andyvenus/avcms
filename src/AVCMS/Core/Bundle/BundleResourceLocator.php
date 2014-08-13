<?php
/**
 * User: Andy
 * Date: 11/08/2014
 * Time: 16:58
 */

namespace AVCMS\Core\Bundle;

use AVCMS\Core\Bundle\Exception\NotFoundException;

class BundleResourceLocator
{
    public function __construct($template_dir, $app_dir) {
        $this->app_dir = $app_dir;
        $this->template_dir = $template_dir;
    }

    public function findFileDirectory($bundle_config, $file, $type)
    {
        foreach ($a = $this->getResourceDirs($bundle_config, $type) as $dir) {
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
            $this->template_dir,
            $this->app_dir,
            $bundle_config->directory,
        );

        if (isset($bundle_config->parent_config)) {
            $dirs[] = $bundle_config->parent_config->directory;
        }

        $final_dirs = array();
        foreach ($dirs as $dir) {
            $final_dirs[] = $dir.'/resources/'.$resource_type;
        }

        return $final_dirs;
    }
}