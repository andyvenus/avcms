<?php

namespace AVCMS\Bundles\Comments\Model;

use AV\Model\Entity;

class Comment extends Entity
{
    public function getComment()
    {
        return $this->get("comment");
    }

    public function setComment($value)
    {
        $this->set("comment", $value);
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

    public function getIp()
    {
        return $this->get("ip");
    }

    public function setIp($value)
    {
        $this->set("ip", $value);
    }

    public function setReplies($value)
    {
        $this->set("replies", $value);
    }

    public function getReplies()
    {
        return $this->get("replies");
    }

    public function setThread($value)
    {
        $this->set("thread", $value);
    }

    public function getThread()
    {
        return $this->get("thread");
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