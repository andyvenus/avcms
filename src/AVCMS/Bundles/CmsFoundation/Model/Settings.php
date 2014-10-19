<?php
/**
 * User: Andy
 * Date: 13/08/2014
 * Time: 15:17
 */

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Model;
use AVCMS\Core\SettingsManager\SettingsModelInterface;

class Settings extends Model implements SettingsModelInterface
{
    public function getTable()
    {
        return 'settings';
    }

    public function getSingular()
    {
        return 'setting';
    }

    public function getEntity()
    {
        return null;
    }

    public function getSettings()
    {
        $settings_rows = $this->query()->get(null, \PDO::FETCH_ASSOC);

        $settings = array();
        foreach ($settings_rows as $setting_row) {
            $settings[$setting_row['name']] = $setting_row['value'];
        }

        return $settings;
    }

    public function saveSettings(array $settings, $existing_settings = null)
    {
        foreach ($settings as $name => $value) {
            $setting = array('value' => $value);
            $this->query()->where('name', $name)->update($setting);
        }
    }

    public function addSetting($name, $value, $loader, $owner)
    {
        $this->query()->insert(array('name' => $name, 'value' => $value, 'loader' => $loader, 'owner' => $owner));
    }
}