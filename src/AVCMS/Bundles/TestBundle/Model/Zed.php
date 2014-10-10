<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Entity;

class Zed extends Entity
{
    public function setAuthor($value)
    {
        $this->set("author", $value);
    }

    public function getAuthor()
    {
        return $this->get("author");
    }

    public function setAuthorLink($value)
    {
        $this->set("author_link", $value);
    }

    public function getAuthorLink()
    {
        return $this->get("author_link");
    }

    public function setCategory($value)
    {
        $this->set("category", $value);
    }

    public function getCategory()
    {
        return $this->get("category");
    }

    public function setDescription($value)
    {
        $this->set("description", $value);
    }

    public function getDescription()
    {
        return $this->get("description");
    }

    public function getFeatured()
    {
        return $this->get("featured");
    }

    public function setFeatured($value)
    {
        $this->set("featured", $value);
    }

    public function setFileUrl($value)
    {
        $this->set("file_url", $value);
    }

    public function getFileUrl()
    {
        return $this->get("file_url");
    }

    public function setFogId($value)
    {
        $this->set("fog_id", $value);
    }

    public function getFogId()
    {
        return $this->get("fog_id");
    }

    public function setHeight($value)
    {
        $this->set("height", $value);
    }

    public function getHeight()
    {
        return $this->get("height");
    }

    public function setHighscores($value)
    {
        $this->set("highscores", $value);
    }

    public function getHighscores()
    {
        return $this->get("highscores");
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function setInstructions($value)
    {
        $this->set("instructions", $value);
    }

    public function getInstructions()
    {
        return $this->get("instructions");
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function setTags($value)
    {
        $this->set("tags", $value);
    }

    public function getTags()
    {
        return $this->get("tags");
    }

    public function setThumbUrl($value)
    {
        $this->set("thumb_url", $value);
    }

    public function getThumbUrl()
    {
        return $this->get("thumb_url");
    }

    public function setVisible($value)
    {
        $this->set("visible", $value);
    }

    public function getVisible()
    {
        return $this->get("visible");
    }

    public function setWidth($value)
    {
        $this->set("width", $value);
    }

    public function getWidth()
    {
        return $this->get("width");
    }
}