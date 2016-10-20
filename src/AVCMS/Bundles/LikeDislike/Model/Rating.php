<?php

namespace AVCMS\Bundles\LikeDislike\Model;

use AV\Model\Entity;

class Rating extends Entity
{
    public function setContentId($value)
    {
        $this->set("content_id", $value);
    }

    public function getContentId()
    {
        return $this->get("content_id");
    }

    public function getContentType()
    {
        return $this->get("content_type");
    }

    public function setContentType($value)
    {
        $this->set("content_type", $value);
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

    public function setRating($value)
    {
        $this->set("rating", $value);
    }

    public function getRating()
    {
        return $this->get("rating");
    }

    public function setUserId($value)
    {
        $this->set("user_id", $value);
    }

    public function getUserId()
    {
        return $this->get("user_id");
    }

    public function setIp($value)
    {
        $this->set("ip", $value);
    }

    public function getIp()
    {
        return $this->get("ip");
    }
}
