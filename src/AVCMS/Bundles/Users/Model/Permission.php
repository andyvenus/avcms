<?php

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Entity;

class Permission extends Entity
{
    public function getDescription()
    {
        return $this->get("description");
    }

    public function setDescription($value)
    {
        $this->set("description", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getLoader()
    {
        return $this->get("loader");
    }

    public function setLoader($value)
    {
        $this->set("loader", $value);
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function getOwner()
    {
        return $this->get("owner");
    }

    public function setOwner($value)
    {
        $this->set("owner", $value);
    }
}