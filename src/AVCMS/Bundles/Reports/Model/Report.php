<?php

namespace AVCMS\Bundles\Reports\Model;

use AV\Model\Entity;

class Report extends Entity
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

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setMessage($value)
    {
        $this->set("message", $value);
    }

    public function getMessage()
    {
        return $this->get("message");
    }

    public function setReportedUserId($value)
    {
        $this->set("reported_user_id", $value);
    }

    public function getReportedUserId()
    {
        return $this->get("reported_user_id");
    }

    public function getSenderUserId()
    {
        return $this->get("sender_user_id");
    }

    public function setSenderUserId($value)
    {
        $this->set("sender_user_id", $value);
    }
}