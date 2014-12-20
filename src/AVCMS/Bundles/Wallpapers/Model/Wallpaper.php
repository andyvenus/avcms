<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AV\Model\Entity;

class Wallpaper extends Entity
{
    public function setCategoryId($value)
    {
        $this->set("category_id", $value);
    }

    public function getCategoryId()
    {
        return $this->get("category_id");
    }

    public function setCategoryParentId($value)
    {
        $this->set("category_parent_id", $value);
    }

    public function getCategoryParentId()
    {
        return $this->get("category_parent_id");
    }

    public function setCreatorId($value)
    {
        $this->set("creator_id", $value);
    }

    public function getCreatorId()
    {
        return $this->get("creator_id");
    }

    public function getCropPosition()
    {
        return $this->get("crop_position");
    }

    public function setCropPosition($value)
    {
        $this->set("crop_position", $value);
    }

    public function setCropType($value)
    {
        $this->set("crop_type", $value);
    }

    public function getCropType()
    {
        return $this->get("crop_type");
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

    public function setDescription($value)
    {
        $this->set("description", $value);
    }

    public function getDescription()
    {
        return $this->get("description");
    }

    public function getEditorId()
    {
        return $this->get("editor_id");
    }

    public function setEditorId($value)
    {
        $this->set("editor_id", $value);
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

    public function getPublished()
    {
        return $this->get("published");
    }

    public function setPublished($value)
    {
        $this->set("published", $value);
    }

    public function getSlug()
    {
        return $this->get("slug");
    }

    public function setSlug($value)
    {
        $this->set("slug", $value);
    }

    public function getTotalDownloads()
    {
        return $this->get("total_downloads");
    }

    public function setTotalDownloads($value)
    {
        $this->set("total_downloads", $value);
    }
}
