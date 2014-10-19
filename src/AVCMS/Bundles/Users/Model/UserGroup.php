<?php

namespace AVCMS\Bundles\Users\Model;

use AV\Model\Entity;

class UserGroup extends Entity
{
    public function getAdminDefault()
    {
        return $this->get("admin_default");
    }

    public function setAdminDefault($value)
    {
        $this->set("admin_default", $value);
    }

    public function getCustomPermissions()
    {
        return $this->get("custom_permissions");
    }

    public function setCustomPermissions($value)
    {
        $this->set("custom_permissions", $value);
    }

    public function setFloodControlTime($value)
    {
        $this->set("flood_control_time", $value);
    }

    public function getFloodControlTime()
    {
        return $this->get("flood_control_time");
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

    public function getOwner()
    {
        return $this->get("owner");
    }

    public function setOwner($value)
    {
        $this->set("owner", $value);
    }

    public function setPermDefault($value)
    {
        $this->set("perm_default", $value);
    }

    public function getPermDefault()
    {
        return $this->get("perm_default");
    }
}