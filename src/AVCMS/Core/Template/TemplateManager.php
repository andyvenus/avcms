<?php
/**
 * User: Andy
 * Date: 18/08/2014
 * Time: 17:22
 */

namespace AVCMS\Core\Template;

use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\Yaml\Yaml;

class TemplateManager
{
    protected $current_template;

    protected $template_config;

    public function __construct(SettingsManager $settingsManager)
    {
        $this->current_template = $settingsManager->getSetting('template');

        if ($this->current_template == null) {
            throw new \Exception("No template set in user settings");
        }
    }

    public function getTemplateConfig()
    {
        if (!isset($this->template_config)) {
            $config_path = $this->current_template.'/config/template.yml';
            if (file_exists($config_path)) {
                $this->template_config = Yaml::parse(file_get_contents($config_path));
            }
            else {
                $this->template_config = array();
            }
        }

        return $this->template_config;
    }
} 