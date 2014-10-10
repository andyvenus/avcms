<?php

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Entity;

class PasswordReset extends Entity
{
    public function setCode($value)
    {
        $this->set("code", $value);
    }

    public function getCode()
    {
        return $this->get("code");
    }

    public function setGenerated($value)
    {
        $this->set("generated", $value);
    }

    public function getGenerated()
    {
        return $this->get("generated");
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