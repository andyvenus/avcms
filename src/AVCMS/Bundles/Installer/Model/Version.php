<?php

namespace AVCMS\Bundles\Installer\Model;

use AV\Model\Entity;

class Version extends Entity
{
    public function getIdentifier()
    {
        return $this->get("identifier");
    }

    public function setIdentifier($value)
    {
        $this->set("identifier", $value);
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