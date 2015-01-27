<?php

namespace AVCMS\Bundles\TranslationHelper\Model;

use AV\Model\Entity;

class Translation extends Entity
{
    public function setCountry($value)
    {
        $this->set("country", $value);
    }

    public function getCountry()
    {
        return $this->get("country");
    }

    public function getCreatorId()
    {
        return $this->get("creator_id");
    }

    public function setCreatorId($value)
    {
        $this->set("creator_id", $value);
    }

    public function setDownloads($value)
    {
        $this->set("downloads", $value);
    }

    public function getDownloads()
    {
        return $this->get("downloads");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function getLangId()
    {
        $name = $this->getLanguage();

        if ($this->getCountry()) {
            $name .= '_'.$this->getCountry();
        }

        return $name;
    }

    public function getLanguage()
    {
        return $this->get("language");
    }

    public function setLanguage($value)
    {
        $this->set("language", $value);
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function getPublic()
    {
        return $this->get("public");
    }

    public function setPublic($value)
    {
        $this->set("public", $value);
    }

    public function getTotalTranslated()
    {
        return $this->get("total_translated");
    }

    public function setTotalTranslated($value)
    {
        $this->set("total_translated", $value);
    }

    public function setUserId($value)
    {
        $this->set("user_id", $value);
    }

    public function getUserId()
    {
        return $this->get("user_id");
    }
}