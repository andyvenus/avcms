<?php
/**
 * User: Andy
 * Date: 15/08/2014
 * Time: 12:47
 */

namespace AVCMS\Core\SettingsManager;

use AVCMS\Core\Bundle\BundleManagerInterface;
use AVCMS\Core\Config\Config;
use AVCMS\Core\SettingsManager\Loader\SettingsLoaderInterface;

class SettingsManager
{
    protected $settings_model;

    protected $loaders;

    public function __construct(SettingsModelInterface $settings_model, BundleManagerInterface $bundle_manager)
    {
        $this->settings_model = $settings_model;
        $this->settings = new Config($this->settings_model->getSettings());
    }

    public function addSettings($settings)
    {
        foreach ($settings as $name => $setting) {
            if (!isset($this->settings[$name])) {
                $this->settings_model->addSetting($name, $setting['value'], $setting['loader'], $setting['owner']);
                $this->settings->setSetting($name, $setting['value']);
            }
        }
    }

    public function load($id, SettingsLoaderInterface $settings_loader)
    {
        $this->loaders[$id] = $settings_loader;

        $settings_loader->getSettings($this);
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