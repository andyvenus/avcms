<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AVCMS\Core\Model\Entity;

class Module extends Entity
{
    protected $template;
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

    public function setLimitRoutesArray($value)
    {
        if (is_array($value)) {
            $value = implode(',', $value);
        }

        $this->setLimitRoutes($value);
    }

    public function getModule()
    {
        return $this->get("module");
    }

    public function setModule($value)
    {
        $this->set("module", $value);
    }

    public function setModuleInfo($moduleInfo)
    {
        $this->moduleInfo = $moduleInfo;
    }

    public function getModuleInfo()
    {
        return $this->moduleInfo;
    }

    public function getOrder()
    {
        return $this->get("order");
    }

    public function setOrder($value)
    {
        $this->set("order", $value);
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
     * Unserializes and gets an array of config data
     *
     * @return mixed
     */
    public function getSettingsArray()
    {
        return unserialize($this->get("settings"));
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

    public function setShowHeader($value)
    {
        $this->set("show_header", $value);
    }

    public function getShowHeader()
    {
        return $this->get("show_header");
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function getTemplateStyle()
    {
        return $this->get("template_style");
    }

    public function setTemplateStyle($value)
    {
        $this->set("template_style", $value);
    }

    public function getTitle()
    {
        return $this->get("title");
    }

    public function setTitle($value)
    {
        $this->set("title", $value);
    }
}