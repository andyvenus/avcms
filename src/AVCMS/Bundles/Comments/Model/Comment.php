<?php

namespace AVCMS\Bundles\Comments\Model;

use AV\Model\Entity;

class Comment extends Entity
{
    public function setComment($value)
    {
        $this->set("comment", $value);
    }

    public function getComment()
    {
        return $this->get("comment");
    }

    public function setContentId($value)
    {
        $this->set("content_id", $value);
    }

    public function getContentId()
    {
        return $this->get("content_id");
    }

    public function getContentTitle()
    {
        return $this->get("content_title");
    }

    public function setContentTitle($value)
    {
        $this->set("content_title", $value);
    }

    public function setContentType($value)
    {
        $this->set("content_type", $value);
    }

    public function getContentType()
    {
        return $this->get("content_type");
    }

    public function setDate($value)
    {
        $this->set("date", $value);
    }

    public function getDate()
    {
        return $this->get("date");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function getIp()
    {
        return $this->get("ip");
    }

    public function setIp($value)
    {
        $this->set("ip", $value);
    }

    public function getUserId()
    {
        return $this->get("user_id");
    }

    public function setUserId($value)
    {
        $this->set("user_id", $value);
    }
}