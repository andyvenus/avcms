<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 13:27
 */

namespace AVCMS\Core\Bundle\SettingsLoader;

use AV\Kernel\Bundle\BundleManagerInterface;
use AVCMS\Core\SettingsManager\Loader\SettingsLoaderInterface;
use AVCMS\Core\SettingsManager\SettingsManager;

class BundleSettingsLoader implements SettingsLoaderInterface
{
    protected $bundleSettings;

    protected $bundleFields = array();

    protected $fieldSections = array();

    protected $bundleManager;

    public function __construct(BundleManagerInterface $bundleManager)
    {
        $this->bundleManager = $bundleManager;
    }

    public function getSettings(SettingsManager $settingsManager)
    {
        if (!$this->bundleManager->cacheIsFresh()) {
            $settingsManager->addSettings($this->loadSettings());
        }
    }

    protected function loadSettings()
    {
        if (isset($this->bundleSettings)) {
            return $this->bundleSettings;
        }

        $bundleSettings = array();
        $bundleConfigs = $this->bundleManager->getBundleConfigs();
        foreach ($bundleConfigs as $bundleConfig) {
            if (isset($bundleConfig['admin_settings']) && !empty($bundleConfig['admin_settings'])) {
                foreach ($bundleConfig['admin_settings'] as $settingName => $setting) {
                    $bundleSettings[$settingName] = array('value' => (isset($setting['default']) ? $setting['default'] : ''), 'loader' => self::getId(), 'owner' => $bundleConfig->name);
                    $this->bundleFields[$settingName] = $setting;
                }

                if ($bundleConfig['admin_settings_sections']) {
                    foreach ($bundleConfig['admin_settings_sections'] as $id => $label) {
                        $this->fieldSections[$id] = array('label' => $label);
                    }
                }
            }
        }

        $this->bundleSettings = $bundleSettings;
        return $this->bundleSettings;
    }


    public function getFields()
    {
        $this->loadSettings();

        return $this->bundleFields;
    }

    public function getSections()
    {
        $this->loadSettings();

        return $this->fieldSections;
    }

    public function hasOwner($owner)
    {
        return $this->bundleManager->hasBundle($owner);
    }

    public static function getId()
    {
        return 'bundle';
    }
}
