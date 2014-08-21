<?php
/**
 * User: Andy
 * Date: 15/08/2014
 * Time: 12:47
 */

namespace AVCMS\Core\SettingsManager;

use AVCMS\Core\Bundle\BundleManagerInterface;
use AVCMS\Core\Config\Config;

class SettingsManager
{
    protected $settings_model;

    public function __construct(SettingsModelInterface $settings_model, BundleManagerInterface $bundle_manager)
    {
        $this->settings_model = $settings_model;
        $this->settings = new Config($this->settings_model->getSettings());

        $bundle_manager->getBundleSettings($this);
    }

    public function addSettings($settings)
    {
        foreach ($settings as $name => $value) {
            if (!isset($this->settings[$name])) {
                $this->settings_model->addSetting($name, $value);
                $this->settings->setSetting($name, $value);
            }
        }
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($name)
    {
        return (isset($this->settings[$name]) ? $this->settings[$name] : null);
    }

    public function __get($name)
    {
        return $this->getSetting($name);
    }

    public function __isset($name)
    {
        return isset($this->settings[$name]);
    }
}