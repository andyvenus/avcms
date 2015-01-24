<?php
/**
 * User: Andy
 * Date: 15/08/2014
 * Time: 12:47
 */

namespace AVCMS\Core\SettingsManager;

use AVCMS\Core\Config\Config;
use AVCMS\Core\SettingsManager\Loader\SettingsLoaderInterface;

class SettingsManager
{
    protected $settingsModel;

    protected $loaders;

    protected $settings;

    public function __construct(SettingsModelInterface $settingsModel)
    {
        $this->settingsModel = $settingsModel;
        $this->settings = new Config($this->settingsModel->getSettings());
    }

    public function addSettings($settings)
    {
        foreach ($settings as $name => $setting) {
            if (!isset($this->settings[$name])) {
                $this->settingsModel->addSetting($name, $setting['value'], $setting['loader'], $setting['owner']);
                $this->settings->setSetting($name, $setting['value']);
            }
        }
    }

    public function load($id, SettingsLoaderInterface $settingsLoader)
    {
        $this->loaders[$id] = $settingsLoader;

        $settingsLoader->getSettings($this);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($name)
    {
        return (isset($this->settings[$name]) ? $this->settings[$name] : null);
    }

    public function getFields()
    {
        $fields = array();
        foreach ($this->loaders as $loader) {
            $fields = array_merge($fields, $loader->getFields());
        }

        return $fields;
    }

    public function getSections()
    {
        $sections = array();
        foreach ($this->loaders as $loader) {
            $sections = array_merge($sections, $loader->getSections());
        }

        return $sections;
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
