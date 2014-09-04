<?php
/**
 * User: Andy
 * Date: 15/08/2014
 * Time: 12:48
 */

namespace AVCMS\Core\SettingsManager;


interface SettingsModelInterface
{
    public function getSettings();

    public function saveSettings(array $settings, $existing_settings = null);

    public function addSetting($name, $value, $loader, $owner);
} 