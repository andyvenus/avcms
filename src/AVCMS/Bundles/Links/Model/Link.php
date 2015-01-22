<?php

namespace AVCMS\Bundles\Links\Model;

use AV\Model\Entity;

class Link extends Entity
{
    public function getAdminSeen()
    {
        return $this->get("admin_seen");
    }

    public function setAdminSeen($value)
    {
        $this->set("admin_seen", $value);
    }

    public function setAnchor($value)
    {
        $this->set("anchor", $value);
    }

    public function getAnchor()
    {
        return $this->get("anchor");
    }

    public function setDateAdded($value)
    {
        $this->set("date_added", $value);
    }

    public function getDateAdded()
    {
        return $this->get("date_added");
    }

    public function getDateEdited()
    {
        return $this->get("date_edited");
    }

    public function setDateEdited($value)
    {
        $this->set("date_edited", $value);
    }

    public function getDescription()
    {
        return $this->get("description");
    }

    public function setDescription($value)
    {
        $this->set("description", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getPublished()
    {
        return $this->get("published");
    }

    public function setPublished($value)
    {
        $this->set("published", $value);
    }

    public function getReferralId()
    {
        return $this->get("referral_id");
    }

    public function setReferralId($value)
    {
        $this->set("referral_id", $value);
    }

    public function setUrl($value)
    {
        $this->set("url", $value);
    }

    public function getUrl()
    {
        return $this->get("url");
    }
}