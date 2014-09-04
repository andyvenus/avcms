<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 13:27
 */

namespace AVCMS\Core\Bundle\SettingsLoader;

use AVCMS\Core\Bundle\BundleManagerInterface;
use AVCMS\Core\SettingsManager\Loader\SettingsLoaderInterface;
use AVCMS\Core\SettingsManager\SettingsManager;

class BundleSettingsLoader implements SettingsLoaderInterface
{
    protected $bundle_settings;

    protected $bundle_fields = array();

    protected $field_sections = array();

    public function __construct(BundleManagerInterface $bundle_manager)
    {
        $this->bundle_manager = $bundle_manager;
    }

    public function getSettings(SettingsManager $settings_manager)
    {
        $settings_manager->addSettings($this->loadSettings());
    }

    protected function loadSettings()
    {
        if (isset($this->bundle_settings)) {
            return $this->bundle_settings;
        }

        $bundle_settings = array();
        $bundle_configs = $this->bundle_manager->getBundleConfigs();
        foreach ($bundle_configs as $bundle_config) {
            if (isset($bundle_config['user_settings']) && !empty($bundle_config['user_settings'])) {
                foreach ($bundle_config['user_settings'] as $setting_name => $setting) {
                    $bundle_settings[$setting_name] = array('value' => (isset($setting['default']) ? $setting['default'] : ''), 'loader' => self::getId(), 'owner' => $bundle_config->name);
                    $this->bundle_fields[$setting_name] = $setting;
                }

                if ($bundle_sections = $bundle_config['user_settings_sections']) {
                    foreach ($bundle_config['user_settings_sections'] as $id => $label) {
                        $this->field_sections[$id] = array('label' => $label);
                    }
                }
            }
        }

        $this->bundle_settings = $bundle_settings;
        return $this->bundle_settings;
    }


    public function getFields()
    {
        return $this->bundle_fields;
    }

    public function getSections()
    {
        return $this->field_sections;
    }

    public function hasOwner($owner)
    {
        return $this->bundle_manager->hasBundle($owner);
    }

    public static function getId()
    {
        return 'bundle';
    }
}