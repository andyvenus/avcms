<?php
/**
 * User: Andy
 * Date: 02/01/15
 * Time: 11:19
 */

namespace AVCMS\Bundles\Wallpapers\ResolutionsManager;

use Symfony\Component\Yaml\Yaml;

class ResolutionsManager
{
    protected $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function getResolutions()
    {
        return Yaml::parse(file_get_contents($this->getConfigPath()));
    }

    public function getResolutionsConfig()
    {
        return file_get_contents($this->getConfigPath());
    }

    public function saveResolutionsConfig($config)
    {
        Yaml::parse($config);

        file_put_contents($this->rootDir.'/webmaster/wallpaper_resolutions.yml', $config);
    }

    private function getConfigPath()
    {
        $configPath = $this->rootDir.'/webmaster/wallpaper_resolutions.yml';

        if (!file_exists($configPath)) {
            $configPath = __DIR__.'/../config/wallpaper_resolutions.yml';
        }

        return $configPath;
    }
}
