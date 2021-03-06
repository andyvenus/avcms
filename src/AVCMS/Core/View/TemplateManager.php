<?php
/**
 * User: Andy
 * Date: 18/08/2014
 * Time: 17:22
 */

namespace AVCMS\Core\View;

use AVCMS\Core\SettingsManager\SettingsManager;
use AVCMS\Core\View\SettingsLoader\TemplateSettingsLoader;
use Symfony\Component\Yaml\Yaml;

class TemplateManager
{
    protected $currentTemplate;

    protected $templateConfig;

    protected $cacheFile;

    protected $devMode;

    protected $cacheFresh = true;

    public function __construct(SettingsManager $settingsManager, $devMode = false, $cacheDir = 'cache')
    {
        $this->currentTemplate = $settingsManager->getSetting('template');
        $this->cacheFile = $cacheDir.'/template_config.php';
        $this->devMode = $devMode;

        if ($this->currentTemplate === null) {
            throw new \Exception("No template set in user settings");
        }

        $this->getTemplateConfig();

        $templateSettingsProvider = new TemplateSettingsLoader($this);
        $settingsManager->load('template', $templateSettingsProvider);

    }

    public function getTemplateConfig()
    {
        if (!file_exists($this->currentTemplate.'/template.yml')) {
            return [];
        }

        if (isset($this->templateConfig)) {
            return $this->templateConfig;
        }

        if (!file_exists($this->cacheFile)) {
            $this->cacheFresh = false;
            $templateConfig = ['template_dir' => null];
        }
        else {
            $templateConfig = include $this->cacheFile;
        }

        if ($this->cacheFresh && $this->devMode === true) {
            if (filemtime($this->cacheFile) < filemtime($this->currentTemplate.'/template.yml')) {
                $this->cacheFresh = false;
            }
            elseif (isset($templateConfig['parent_template_dir']) && filemtime($this->cacheFile) < filemtime($templateConfig['parent_template_dir'].'/template.yml')) {
                $this->cacheFresh = false;
            }
        }
        elseif ($templateConfig['template_dir'] !== $this->currentTemplate) {
            $this->cacheFresh = false;
        }

        if ($this->cacheFresh === false) {
            $templateConfig = Yaml::parse(file_get_contents($this->currentTemplate.'/template.yml'));
            $templateConfig['template_dir'] = $this->currentTemplate;

            if (isset($templateConfig['parent'])) {
                $parentTemplateDir = $this->currentTemplate.'/../'.$templateConfig['parent'];
                $templateConfig['parent_template_dir'] = $parentTemplateDir;

                $parentTemplateConfig = Yaml::parse(file_get_contents($parentTemplateDir.'/template.yml'));

                $templateConfig = array_replace_recursive($parentTemplateConfig, $templateConfig);
            }

            file_put_contents($this->cacheFile, '<?php return '.var_export($templateConfig, true).';');
        }

        return $this->templateConfig = $templateConfig;
    }

    public function cacheIsFresh()
    {
        return $this->cacheFresh;
    }

    public function getCurrentTemplate()
    {
        return $this->currentTemplate;
    }
} 
