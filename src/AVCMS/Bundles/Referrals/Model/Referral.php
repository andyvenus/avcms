<?php

namespace AVCMS\Bundles\Referrals\Model;

use AV\Model\Entity;

class Referral extends Entity
{
    public function setConversions($value)
    {
        $this->set("conversions", $value);
    }

    public function getConversions()
    {
        return $this->get("conversions");
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getInbound()
    {
        return $this->get("inbound");
    }

    public function setInbound($value)
    {
        $this->set("inbound", $value);
    }

    public function setLastReferral($value)
    {
        $this->set("last_referral", $value);
    }

    public function getLastReferral()
    {
        return $this->get("last_referral");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function getOutbound()
    {
        return $this->get("outbound");
    }

    public function setOutbound($value)
    {
        $this->set("outbound", $value);
    }

    public function setType($value)
    {
        $this->set("type", $value);
    }

    public function getType()
    {
        return $this->get("type");
    }

    public function getUserId()
    {
        return $this->get("user_id");
    }

    public function setUserId($value)
    {
        $this->set("user_id", $value);
    }

    public function getUserIp()
    {
        return $this->get("user_ip");
    }

    public function setUserIp($value)
    {
        $this->set("user_ip", $value);
    }
}
