<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;
use AVCMS\Core\Menu\MenuItemConfigInterface;

class MenuItemConfig extends Entity implements MenuItemConfigInterface
{
    protected $url;

    protected $settingsArray;

    public function setEnabled($value)
    {
        $this->set("enabled", $value);
    }

    public function getEnabled()
    {
        return $this->get("enabled");
    }

    public function setProviderEnabled($value)
    {
        $this->set("provider_enabled", $value);
    }

    public function getProviderEnabled()
    {
        return $this->get("provider_enabled");
    }

    public function setIcon($value)
    {
        $this->set("icon", $value);
    }

    public function getIcon()
    {
        return $this->get("icon");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function getLabel()
    {
        return $this->get("label");
    }

    public function setLabel($value)
    {
        $this->set("label", $value);
    }

    public function setMenu($value)
    {
        $this->set("menu", $value);
    }

    public function getMenu()
    {
        return $this->get("menu");
    }

    public function getOrder()
    {
        return $this->get("order");
    }

    public function setOrder($value)
    {
        $this->set("order", $value);
    }

    public function setOwner($value)
    {
        $this->set("owner", $value);
    }

    public function getOwner()
    {
        return $this->get("owner");
    }

    public function setProviderId($id)
    {
        $this->set('provider_id', $id);
    }

    public function getProviderId()
    {
        return $this->get('provider_id');
    }

    public function setParent($value)
    {
        $this->set("parent", $value);
    }

    public function getParent()
    {
        return $this->get("parent");
    }

    public function setPermission($value)
    {
        $this->set("permission", $value);
    }

    public function getPermission()
    {
        return $this->get("permission");
    }

    public function getTranslatable()
    {
        return $this->get("translatable");
    }

    public function setTranslatable($value)
    {
        $this->set("translatable", $value);
    }

    public function getType()
    {
        return $this->get("type");
    }

    public function setType($value)
    {
        $this->set("type", $value);
    }

    public function setAdminSetting($value)
    {
        $this->set("admin_setting", $value);
    }

    public function getAdminSetting()
    {
        return $this->get("admin_setting");
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getSettingsSerial()
    {
        return $this->get('settings_serial');
    }

    public function setSettingsSerial($value)
    {
        $this->set("settings_serial", $value);
    }

    /**
     * Serializes and sets an array of config data
     *
     * @param $value
     * @return mixed
     */
    public function setSettings($value)
    {
        unset($this->settingsArray);
        $this->set("settings_serial", serialize($value));
    }

    /**
     * Unserializes and gets an array of config data
     *
     * @return mixed
     */
    public function getSettings()
    {
        if (isset($this->settingsArray)) {
            return $this->settingsArray;
        }
        if (is_array($settings = unserialize($this->get("settings_serial")))) {
            return $settings;
        }
        else {
            return array();
        }
    }

    public function getSetting($setting)
    {
        $settings = $this->getSettings();

        return isset($settings[$setting]) ? $settings[$setting] : null;
    }
}
