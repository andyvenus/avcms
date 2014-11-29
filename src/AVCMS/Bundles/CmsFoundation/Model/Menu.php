<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;

class Menu extends Entity
{
    public function getActive()
    {
        return $this->get("active");
    }

    public function setActive($value)
    {
        $this->set("active", $value);
    }

    public function getCustom()
    {
        return $this->get("custom");
    }

    public function setCustom($value)
    {
        $this->set("custom", $value);
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

    public function getOwner()
    {
        return $this->get("owner");
    }

    public function setOwner($value)
    {
        $this->set("owner", $value);
    }

    public function getProvider()
    {
        return $this->get("provider");
    }

    public function setProvider($value)
    {
        $this->set("provider", $value);
    }
}