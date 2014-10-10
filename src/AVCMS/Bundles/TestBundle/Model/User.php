<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Entity;

class User extends Entity
{
    public function setAbout($value)
    {
        $this->set("about", $value);
    }

    public function getAbout()
    {
        return $this->get("about");
    }

    public function setAvatar($value)
    {
        $this->set("avatar", $value);
    }

    public function getAvatar()
    {
        return $this->get("avatar");
    }

    public function setCoverImage($value)
    {
        $this->set("cover_image", $value);
    }

    public function getCoverImage()
    {
        return $this->get("cover_image");
    }

    public function setEmail($value)
    {
        $this->set("email", $value);
    }

    public function getEmail()
    {
        return $this->get("email");
    }

    public function setEmailValidated($value)
    {
        $this->set("email_validated", $value);
    }

    public function getEmailValidated()
    {
        return $this->get("email_validated");
    }

    public function setFacebook($value)
    {
        $this->set("facebook", $value);
    }

    public function getFacebook()
    {
        return $this->get("facebook");
    }

    public function setFacebookId($value)
    {
        $this->set("facebook_id", $value);
    }

    public function getFacebookId()
    {
        return $this->get("facebook_id");
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function setInterests($value)
    {
        $this->set("interests", $value);
    }

    public function getInterests()
    {
        return $this->get("interests");
    }

    public function getJoined()
    {
        return $this->get("joined");
    }

    public function setJoined($value)
    {
        $this->set("joined", $value);
    }

    public function getLastActivity()
    {
        return $this->get("last_activity");
    }

    public function setLastActivity($value)
    {
        $this->set("last_activity", $value);
    }

    public function getLastip()
    {
        return $this->get("lastip");
    }

    public function setLastip($value)
    {
        $this->set("lastip", $value);
    }

    public function setLocation($value)
    {
        $this->set("location", $value);
    }

    public function getLocation()
    {
        return $this->get("location");
    }

    public function getPassword()
    {
        return $this->get("password");
    }

    public function setPassword($value)
    {
        $this->set("password", $value);
    }

    public function getRoleList()
    {
        return $this->get("role_list");
    }

    public function setRoleList($value)
    {
        $this->set("role_list", $value);
    }

    public function setSlug($value)
    {
        $this->set("slug", $value);
    }

    public function getSlug()
    {
        return $this->get("slug");
    }

    public function getTimezone()
    {
        return $this->get("timezone");
    }

    public function setTimezone($value)
    {
        $this->set("timezone", $value);
    }

    public function setUsername($value)
    {
        $this->set("username", $value);
    }

    public function getUsername()
    {
        return $this->get("username");
    }

    public function getWebsite()
    {
        return $this->get("website");
    }

    public function setWebsite($value)
    {
        $this->set("website", $value);
    }
}