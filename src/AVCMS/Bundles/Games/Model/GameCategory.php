<?php

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Entity;

class GameCategory extends Entity
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

    public function getName()
    {
        return $this->get("name");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function setOrder($value)
    {
        $this->set("order", $value);
    }

    public function getOrder()
    {
        return $this->get("order");
    }

    public function setParent($value)
    {
        $this->set("parent", $value);
    }

    public function getParent()
    {
        return $this->get("parent");
    }

    public function setSlug($value)
    {
        $this->set("slug", $value);
    }

    public function getSlug()
    {
        return $this->get("slug");
    }
}