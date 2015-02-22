<?php

namespace AVCMS\Bundles\PrivateMessages\Model;

use AV\Model\Entity;

class PrivateMessage extends Entity
{
    public function setBody($value)
    {
        $this->set("body", $value);
    }

    public function getBody()
    {
        return $this->get("body");
    }

    public function getDate()
    {
        return $this->get("date");
    }

    public function setDate($value)
    {
        $this->set("date", $value);
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

    public function setRead($value)
    {
        $this->set("read", $value);
    }

    public function getRead()
    {
        return $this->get("read");
    }

    public function setRecipientId($value)
    {
        $this->set("recipient_id", $value);
    }

    public function getRecipientId()
    {
        return $this->get("recipient_id");
    }

    public function getSenderId()
    {
        return $this->get("sender_id");
    }

    public function setSenderId($value)
    {
        $this->set("sender_id", $value);
    }

    public function getSubject()
    {
        return $this->get("subject");
    }

    public function setSubject($value)
    {
        $this->set("subject", $value);
    }
}