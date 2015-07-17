<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;

class Hit extends Entity
{
    public function getColumn()
    {
        return $this->get("column");
    }

    public function setColumn($value)
    {
        $this->set("column", $value);
    }

    public function setContentId($value)
    {
        $this->set("content_id", $value);
    }

    public function getContentId()
    {
        return $this->get("content_id");
    }

    public function setDate($value)
    {
        $this->set("date", $value);
    }

    public function getDate()
    {
        return $this->get("date");
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getIp()
    {
        return $this->get("ip");
    }

    public function setIp($value)
    {
        $this->set("ip", $value);
    }

    public function getType()
    {
        return $this->get("type");
    }

    public function setType($value)
    {
        $this->set("type", $value);
    }
}