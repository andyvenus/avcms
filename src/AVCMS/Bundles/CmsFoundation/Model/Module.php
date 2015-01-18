<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;
use AVCMS\Core\Module\ModuleConfigInterface;

class Module extends Entity implements ModuleConfigInterface
{
    protected $moduleInfo;
    protected $content;

    public function setActive($value)
    {
        $this->set("active", $value);
    }

    public function getActive()
    {
        return $this->get("active");
    }

    public function getCacheTime()
    {
        return $this->get("cache_time");
    }

    public function setCacheTime($value)
    {
        $this->set("cache_time", $value);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getLimitRoutes()
    {
        return $this->get("limit_routes");
    }

    public function setLimitRoutes($value)
    {
        $this->set("limit_routes", $value);
    }

    public function setLimitRoutesArray($value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $this->setLimitRoutes($value);
    }

    public function getLimitRoutesArray()
    {
        $routes = $this->getLimitRoutes();

        if ($routes) {
            return explode(',', $routes);
        }
        else {
            return null;
        }
    }

    public function getModule()
    {
        return $this->get("module");
    }

    public function setModule($value)
    {
        $this->set("module", $value);
    }

    public function getModuleInfo()
    {
        return $this->moduleInfo;
    }

    public function setModuleInfo($moduleInfo)
    {
        $this->moduleInfo = $moduleInfo;
    }

    public function getOrder()
    {
        return $this->get("order");
    }

    public function setOrder($value)
    {
        $this->set("order", $value);
    }

    public function setPermissions($value)
    {
        $this->set("permissions", $value);
    }

    public function getPermissions()
    {
        return $this->get("permissions");
    }

    public function setPublished($value)
    {
        $this->set("published", $value);
    }

    public function getPublished()
    {
        return $this->get("published");
    }

    public function setPermissionsArray($value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $this->setPermissions($value);
    }

    public function getPermissionsArray()
    {
        $permissions = $this->getPermissions();

        if ($permissions) {
            return explode(',', $permissions);
        }
        else {
            return null;
        }
    }

    public function getPosition()
    {
        return $this->get("position");
    }

    public function setPosition($value)
    {
        $this->set("position", $value);
    }

    public function setSettings($value)
    {
        $this->set("settings", $value);
    }

    public function getSettings()
    {
        return $this->get("settings");
    }

    /**
     * Serializes and sets an array of config data
     *
     * @param $value
     * @return mixed
     */
    public function setSettingsArray($value)
    {
        $this->set("settings", serialize($value));
    }

    /**
     * Unserializes and gets an array of config data
     *
     * @return mixed
     */
    public function getSettingsArray()
    {
        if (is_array($settings = unserialize($this->get("settings")))) {
            return $settings;
        }
        else {
            return array();
        }
    }

    public function getShowHeader()
    {
        return $this->get("show_header");
    }

    public function setShowHeader($value)
    {
        $this->set("show_header", $value);
    }

    public function getTemplate()
    {
        return $this->get("template");
    }

    public function setTemplate($value)
    {
        $this->set("template", $value);
    }

    public function getTemplateType()
    {
        return $this->get("template_type");
    }

    public function setTemplateType($value)
    {
        $this->set("template_type", $value);
    }

    public function setTitle($value)
    {
        $this->set("title", $value);
    }

    public function getTitle()
    {
        return $this->get("title");
    }
}
