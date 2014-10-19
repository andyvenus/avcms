<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;

class Menu extends Entity
{
    public function getCustom()
    {
        return $this->get("custom");
    }

    public function setCustom($value)
    {
        $this->set("custom", $value);
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setLabel($value)
    {
        $this->set("label", $value);
    }

    public function getLabel()
    {
        return $this->get("label");
    }
}