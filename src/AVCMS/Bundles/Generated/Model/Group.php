<?php

namespace AVCMS\Bundles\Generated\Model;

use AVCMS\Core\Model\Entity;

class Group extends Entity
{
    public function getDoublePostTime()
    {
        return $this->get("double_post_time");
    }

    public function setDoublePostTime($value)
    {
        $this->set("double_post_time", $value);
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

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function getName()
    {
        return $this->get("name");
    }
}