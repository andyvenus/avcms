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

    protected $fieldSections = array();

    protected $templateManager;

    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    public function getSettings(SettingsManager $settingsManager)
    {
        if (!$this->templateManager->cacheIsFresh()) {
            $settingsManager->addSettings($this->loadSettings());
        }
    }

    protected function loadSettings()
    {
        if (isset($this->settings)) {
            return $this->settings;
        }

        $config = $this->templateManager->getTemplateConfig();

        $settings = array();
        if (isset($config['admin_settings']) && !empty($config['admin_settings'])) {
            foreach ($config['admin_settings'] as $setting_name => $setting) {
                $settings[$setting_name] = array(
                    'value' => (isset($setting['default']) ? $setting['default'] : ''),
                    'loader' => self::getId(),
                    'owner' => $this->templateManager->getTemplateConfig()['details']['name']
                );
                $this->fields[$setting_name] = $setting;
            }

            if (isset($config['admin_settings_sections']) && !empty($config['admin_settings_sections'])) {
                foreach ($config['admin_settings_sections'] as $id => $label) {
                    $this->fieldSections[$id] = array('label' => $label);
                }
            }
        }

        $this->settings = $settings;
        return $this->settings;
    }

    public function hasOwner($owner)
    {
        return ($this->templateManager->getTemplateConfig()['name'] == $owner);
    }

    public static function getId()
    {
        return 'template';
    }

    public function getFields()
    {
        $this->loadSettings();

        return $this->fields;
    }

    public function getSections()
    {
        return $this->fieldSections;
    }
}
