<?php

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Entity;

class Game extends Entity
{
    public function setAdvertId($value)
    {
        $this->set("advert_id", $value);
    }

    public function getAdvertId()
    {
        return $this->get("advert_id");
    }

    public function setCategoryId($value)
    {
        $this->set("category_id", $value);
    }

    public function getCategoryId()
    {
        return $this->get("category_id");
    }

    public function setCategoryParent($value)
    {
        $this->set("category_parent", $value);
    }

    public function getCategoryParent()
    {
        return $this->get("category_parent");
    }

    public function setCreatorId($value)
    {
        $this->set("creator_id", $value);
    }

    public function getCreatorId()
    {
        return $this->get("creator_id");
    }

    public function setDateAdded($value)
    {
        $this->set("date_added", $value);
    }

    public function getDateAdded()
    {
        return $this->get("date_added");
    }

    public function setDateEdited($value)
    {
        $this->set("date_edited", $value);
    }

    public function getDateEdited()
    {
        return $this->get("date_edited");
    }

    public function setDescription($value)
    {
        $this->set("description", $value);
    }

    public function getDescription()
    {
        return $this->get("description");
    }

    public function getDislikes()
    {
        return $this->get("dislikes");
    }

    public function setDislikes($value)
    {
        $this->set("dislikes", $value);
    }

    public function setEditorId($value)
    {
        $this->set("editor_id", $value);
    }

    public function getEditorId()
    {
        return $this->get("editor_id");
    }

    public function getEmbedCode()
    {
        return $this->get("embed_code");
    }

    public function setEmbedCode($value)
    {
        $this->set("embed_code", $value);
    }

    public function setFeatured($value)
    {
        $this->set("featured", $value);
    }

    public function getFeatured()
    {
        return $this->get("featured");
    }

    public function getFile()
    {
        return $this->get("file");
    }

    public function setFile($value)
    {
        $this->set("file", $value);
    }

    public function setFiletype($value)
    {
        $this->set("filetype", $value);
    }

    public function getFiletype()
    {
        return $this->get("filetype");
    }

    public function setHeight($value)
    {
        $this->set("height", $value);
    }

    public function getHeight()
    {
        return $this->get("height");
    }

    public function getHits()
    {
        return $this->get("hits");
    }

    public function setHits($value)
    {
        $this->set("hits", $value);
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

    public function setLikes($value)
    {
        $this->set("likes", $value);
    }

    public function getLikes()
    {
        return $this->get("likes");
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function setPublishDate($value)
    {
        $this->set("publish_date", $value);
    }

    public function getPublishDate()
    {
        return $this->get("publish_date");
    }

    public function setPublished($value)
    {
        $this->set("published", $value);
    }

    public function getPublished()
    {
        return $this->get("published");
    }

    public function getSlug()
    {
        return $this->get("slug");
    }

    public function setSlug($value)
    {
        $this->set("slug", $value);
    }

    public function setSubmitterId($value)
    {
        $this->set("submitter_id", $value);
    }

    public function getSubmitterId()
    {
        return $this->get("submitter_id");
    }

    public function setThumbnail($value)
    {
        $this->set("thumbnail", $value);
    }

    public function getThumbnail()
    {
        return $this->get("thumbnail");
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