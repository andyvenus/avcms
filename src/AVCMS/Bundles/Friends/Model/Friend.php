<?php

namespace AVCMS\Bundles\Friends\Model;

use AV\Model\Entity;

class Friend extends Entity
{
    public function getUser1()
    {
        return $this->get("user1");
    }

    public function setUser1($value)
    {
        $this->set("user1", $value);
    }

    public function setUser2($value)
    {
        $this->set("user2", $value);
    }

    public function getUser2()
    {
        return $this->get("user2");
    }
}