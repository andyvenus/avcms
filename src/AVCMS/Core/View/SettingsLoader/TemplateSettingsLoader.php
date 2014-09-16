<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 16:17
 */

namespace AVCMS\Core\View\SettingsLoader;

use AVCMS\Core\SettingsManager\Loader\SettingsLoaderInterface;
use AVCMS\Core\SettingsManager\SettingsManager;
use AVCMS\Core\View\TemplateManager;

class TemplateSettingsLoader implements SettingsLoaderInterface
{
    protected $settings;

    protected $fields = array();

    protected $field_sections = array();

    public function __construct(TemplateManager $template_manager)
    {
        $this->template_manager = $template_manager;
    }

    public function getSettings(SettingsManager $settingsManager)
    {
        $settingsManager->addSettings($this->loadSettings());
    }

    protected function loadSettings()
    {
        if (isset($this->settings)) {
            return $this->settings;
        }

        $config = $this->template_manager->getTemplateConfig();

        $settings = array();
        if (isset($config['user_settings']) && !empty($config['user_settings'])) {
            foreach ($config['user_settings'] as $setting_name => $setting) {
                $settings[$setting_name] = array(
                    'value' => (isset($setting['default']) ? $setting['default'] : ''),
                    'loader' => self::getId(),
                    'owner' => $this->template_manager->getTemplateConfig()['details']['name']
                );
                $this->fields[$setting_name] = $setting;
            }

            if (isset($config['user_settings_sections']) && !empty($config['user_settings_sections'])) {
                foreach ($config['user_settings_sections'] as $id => $label) {
                    $this->field_sections[$id] = array('label' => $label);
                }
            }
        }

        $this->settings = $settings;
        return $this->settings;
    }

    public function hasOwner($owner)
    {
        return ($this->template_manager->getTemplateConfig()['name'] == $owner);
    }

    public static function getId()
    {
        return 'template';
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getSections()
    {
        return $this->field_sections;
    }
}