<?php
/**
 * User: Andy
 * Date: 02/01/15
 * Time: 11:19
 */

namespace AVCMS\Bundles\Wallpapers\ResolutionsManager;

use AVCMS\Bundles\Wallpapers\Model\Wallpaper;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\Yaml\Yaml;

class ResolutionsManager
{
    protected $rootDir;

    protected $resolutions;

    protected $settingsManager;

    protected $cacheDir;

    public function __construct($rootDir, SettingsManager $settingsManager, $cacheDir)
    {
        $this->rootDir = $rootDir;
        $this->settingsManager = $settingsManager;
        $this->cacheDir = $cacheDir;
    }

    public function getAllResolutions()
    {
        if (!isset($this->resolutions)) {
            $cacheFile = $this->cacheDir.'/wallpaper_resolutions.php';

            $cacheTime = 0;
            if (file_exists($cacheFile)) {
                $cacheTime = filemtime($cacheFile);
            }

            $resTime = filemtime($this->getConfigPath());

            if ($resTime > $cacheTime) {
                $this->resolutions = Yaml::parse(file_get_contents($this->getConfigPath()));

                file_put_contents($cacheFile, '<?php return '.var_export($this->resolutions, true).';');
            }
            else {
                $this->resolutions = include $cacheFile;
            }
        }

        return $this->resolutions;
    }

    public function getWallpaperResolutions(Wallpaper $wallpaper)
    {
        $resolutions = $this->getAllResolutions();

        if ($this->settingsManager->getSetting('show_higher_resolutions')) {
            return $resolutions;
        }

        foreach ($resolutions as $resCatId => $resCat) {
            foreach ($resCat as $resolution => $name) {
                $dimensions = explode('x', $resolution);
                if ($dimensions[0] > $wallpaper->getOriginalWidth() || $dimensions[1] > $wallpaper->getOriginalHeight()) {
                    unset($resolutions[$resCatId][$resolution]);
                }
            }
        }

        return $resolutions;
    }

    public function getResolutionsConfig()
    {
        return file_get_contents($this->getConfigPath());
    }

    public function saveResolutionsConfig($config)
    {
        Yaml::parse($config);

        file_put_contents($this->rootDir.'/webmaster/config/wallpaper_resolutions.yml', $config);
    }

    private function getConfigPath()
    {
        $configPath = $this->rootDir.'/webmaster/config/wallpaper_resolutions.yml';

        if (!file_exists($configPath)) {
            $configPath = __DIR__.'/../config/wallpaper_resolutions.yml';
        }

        return $configPath;
    }

    public function getThumbnailResolution($size)
    {
        $resolutions = [
            'xs' => '100x56',
            'sm' => '250x141',
            'md' => '500x281',
            'lg' => '896x504',
            'xl' => '1200x675'
        ];

        if (isset($resolutions[$size])) {
            return $resolutions[$size];
        }

        return null;
    }

    public function checkValidResolution($width, $height, Wallpaper $wallpaper)
    {
        if ($wallpaper->getOriginalWidth() == $width && $wallpaper->getOriginalHeight() == $height) {
            return true;
        }

        $resolution = $width.'x'.$height;

        $resolutions = $this->getAllResolutions();
        foreach ($resolutions as $resCat) {
            foreach ($resCat as $res => $resName) {
                if ($res === $resolution) {
                    return true;
                }
            }
        }

        return false;
    }
}
