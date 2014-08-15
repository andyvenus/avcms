<?php

namespace AVCMS\Bundles\Generated\Model;

use AVCMS\Core\Model\Entity;

class Playtomic extends Entity
{
    public function getAuthor()
    {
        return $this->get("author");
    }

    public function setAuthor($value)
    {
        $this->set("author", $value);
    }

    public function getAuthorLink()
    {
        return $this->get("author_link");
    }

    public function setAuthorLink($value)
    {
        $this->set("author_link", $value);
    }

    public function getCategory()
    {
        return $this->get("category");
    }

    public function setCategory($value)
    {
        $this->set("category", $value);
    }

    public function getDescription()
    {
        return $this->get("description");
    }

    public function setDescription($value)
    {
        $this->set("description", $value);
    }

    public function getFeatured()
    {
        return $this->get("featured");
    }

    public function setFeatured($value)
    {
        $this->set("featured", $value);
    }

    public function getFileUrl()
    {
        return $this->get("file_url");
    }

    public function setFileUrl($value)
    {
        $this->set("file_url", $value);
    }

    public function getGametag()
    {
        return $this->get("gametag");
    }

    public function setGametag($value)
    {
        $this->set("gametag", $value);
    }

    public function getHeight()
    {
        return $this->get("height");
    }

    public function setHeight($value)
    {
        $this->set("height", $value);
    }

    public function getHighscores()
    {
        return $this->get("highscores");
    }

    public function setHighscores($value)
    {
        $this->set("highscores", $value);
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function getInstructions()
    {
        return $this->get("instructions");
    }

    public function setInstructions($value)
    {
        $this->set("instructions", $value);
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function getTags()
    {
        return $this->get("tags");
    }

    public function setTags($value)
    {
        $this->set("tags", $value);
    }

    public function getThumbUrl()
    {
        return $this->get("thumb_url");
    }

    public function setThumbUrl($value)
    {
        $this->set("thumb_url", $value);
    }

    public function getVisible()
    {
        return $this->get("visible");
    }

    public function setVisible($value)
    {
        $this->set("visible", $value);
    }

    public function getWidth()
    {
        return $this->get("width");
    }

    public function setWidth($value)
    {
        $this->set("width", $value);
    }
}