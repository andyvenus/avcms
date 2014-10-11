<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Entity;

class Group extends Entity
{
    public function getAdminDefault()
    {
        return $this->get("admin_default");
    }

    public function setAdminDefault($value)
    {
        $this->set("admin_default", $value);
    }

    public function getFloodControlTime()
    {
        return $this->get("flood_control_time");
    }

    public function setFloodControlTime($value)
    {
        $this->set("flood_control_time", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function setOwner($value)
    {
        $this->set("owner", $value);
    }

    public function getOwner()
    {
        return $this->get("owner");
    }

    public function getPermDefault()
    {
        return $this->get("perm_default");
    }

    public function setPermDefault($value)
    {
        $this->set("perm_default", $value);
    }
}