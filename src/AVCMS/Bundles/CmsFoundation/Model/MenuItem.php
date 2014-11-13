<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;

class MenuItem extends Entity
{
    protected $url;

    public function getEnabled()
    {
        return $this->get("enabled");
    }

    public function setEnabled($value)
    {
        $this->set("enabled", $value);
    }

    public function getIcon()
    {
        return $this->get("icon");
    }

    public function setIcon($value)
    {
        $this->set("icon", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function setLabel($value)
    {
        $this->set("label", $value);
    }

    public function getLabel()
    {
        return $this->get("label");
    }

    public function setMenu($value)
    {
        $this->set("menu", $value);
    }

    public function getMenu()
    {
        return $this->get("menu");
    }

    public function setOrder($value)
    {
        $this->set("order", $value);
    }

    public function getOrder()
    {
        return $this->get("order");
    }

    public function getOwner()
    {
        return $this->get("owner");
    }

    public function setOwner($value)
    {
        $this->set("owner", $value);
    }

    public function setParent($value)
    {
        $this->set("parent", $value);
    }

    public function getParent()
    {
        return $this->get("parent");
    }

    public function getPermission()
    {
        return $this->get("permission");
    }

    public function setPermission($value)
    {
        $this->set("permission", $value);
    }

    public function setTarget($value)
    {
        $this->set("target", $value);
    }

    public function getTarget()
    {
        return $this->get("target");
    }

    public function setType($value)
    {
        $this->set("type", $value);
    }

    public function getType()
    {
        return $this->get("type");
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