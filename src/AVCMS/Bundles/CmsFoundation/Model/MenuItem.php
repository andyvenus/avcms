<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;

class MenuItem extends Entity
{
    protected $url;

    public function setEnabled($value)
    {
        $this->set("enabled", $value);
    }

    public function getEnabled()
    {
        return $this->get("enabled");
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

    public function getTarget()
    {
        return $this->get("target");
    }

    public function setTarget($value)
    {
        $this->set("target", $value);
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

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }
}