<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Entity;

class Token extends Entity
{
    public function getClass()
    {
        return $this->get("class");
    }

    public function setClass($value)
    {
        $this->set("class", $value);
    }

    public function getLastUsed()
    {
        return $this->get("last_used");
    }

    public function setLastUsed($value)
    {
        $this->set("last_used", $value);
    }

    public function setSeries($value)
    {
        $this->set("series", $value);
    }

    public function getSeries()
    {
        return $this->get("series");
    }

    public function getTokenValue()
    {
        return $this->get("token_value");
    }

    public function setTokenValue($value)
    {
        $this->set("token_value", $value);
    }

    public function getUsername()
    {
        return $this->get("username");
    }

    public function setUsername($value)
    {
        $this->set("username", $value);
    }
}