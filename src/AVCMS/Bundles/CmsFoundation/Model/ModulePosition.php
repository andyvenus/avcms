<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AVCMS\Core\Model\Entity;

class ModulePosition extends Entity
{
    public function setActive($value)
    {
        $this->set("active", $value);
    }

    public function getActive()
    {
        return $this->get("active");
    }

    public function setDescription($value)
    {
        $this->set("description", $value);
    }

    public function getDescription()
    {
        return $this->get("description");
    }

    public function getGlobalModules()
    {
        return $this->get("global_modules");
    }

    public function setGlobalModules($value)
    {
        $this->set("global_modules", $value);
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function setOwner($value)
    {
        $this->set("owner", $value);
    }

    public function getOwner()
    {
        return $this->get("owner");
    }

    public function setProvider($value)
    {
        $this->set("provider", $value);
    }

    public function getProvider()
    {
        return $this->get("provider");
    }

    public function getType()
    {
        return $this->get("type");
    }

    public function setType($value)
    {
        $this->set("type", $value);
    }
}