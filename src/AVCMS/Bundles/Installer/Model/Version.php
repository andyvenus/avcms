<?php

namespace AVCMS\Bundles\Installer\Model;

use AV\Model\Entity;

class Version extends Entity
{
    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function setInstalledVersion($value)
    {
        $this->set("installed_version", $value);
    }

    public function getInstalledVersion()
    {
        return $this->get("installed_version");
    }

    public function setType($value)
    {
        $this->set("type", $value);
    }

    public function getType()
    {
        return $this->get("type");
    }
}