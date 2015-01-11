<?php

namespace AVCMS\Bundles\Adverts\Model;

use AV\Model\Entity;

class Advert extends Entity
{
    public function getContent()
    {
        return $this->get("content");
    }

    public function setContent($value)
    {
        $this->set("content", $value);
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }
}