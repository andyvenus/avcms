<?php

namespace AVCMS\Bundles\Friends\Model;

use AV\Model\Entity;

class FriendRequest extends Entity
{
    public function getDate()
    {
        return $this->get("date");
    }

    public function setDate($value)
    {
        $this->set("date", $value);
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setReceiverId($value)
    {
        $this->set("receiver_id", $value);
    }

    public function getReceiverId()
    {
        return $this->get("receiver_id");
    }

    public function setSenderId($value)
    {
        $this->set("sender_id", $value);
    }

    public function getSenderId()
    {
        return $this->get("sender_id");
    }
}