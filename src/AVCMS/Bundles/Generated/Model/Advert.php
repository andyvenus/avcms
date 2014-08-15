<?php

namespace AVCMS\Bundles\Generated\Model;

use AVCMS\Core\Model\Entity;

class Advert extends Entity
{
    public function getAdContent()
    {
        return $this->get("ad_content");
    }

    public function setAdContent($value)
    {
        $this->set("ad_content", $value);
    }

    public function setAdName($value)
    {
        $this->set("ad_name", $value);
    }

    public function getAdName()
    {
        return $this->get("ad_name");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }
}