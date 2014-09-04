<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 13:25
 */

namespace AVCMS\Core\SettingsManager\Loader;

use AVCMS\Core\SettingsManager\SettingsManager;

interface SettingsLoaderInterface
{
    public function getSettings(SettingsManager $settings_manager);

    public function hasOwner($owner);

    public static function getId();

    public function getFields();

    public function getSections();
} 