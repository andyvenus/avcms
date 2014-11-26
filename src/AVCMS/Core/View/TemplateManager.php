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

    public function __construct(SettingsManager $settingsManager)
    {
        $this->currentTemplate = $settingsManager->getSetting('template');

        $templateSettingsProvider = new TemplateSettingsLoader($this);
        $settingsManager->load('template', $templateSettingsProvider);

        if ($this->currentTemplate == null) {
            throw new \Exception("No template set in user settings");
        }
    }

    public function getTemplateConfig()
    {
        if (!isset($this->templateConfig)) {
            $config_path = $this->currentTemplate.'/template.yml';
            if (file_exists($config_path)) {
                $this->templateConfig = Yaml::parse(file_get_contents($config_path));
            }
            else {
                $this->templateConfig = array();
            }
        }

        return $this->templateConfig;
    }

    public function getCurrentTemplate()
    {
        return $this->currentTemplate;
    }
} 