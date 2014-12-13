<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AV\Model\Entity;

class Wallpaper extends Entity
{
    public function setCategory($value)
    {
        $this->set("category", $value);
    }

    public function getCategory()
    {
        return $this->get("category");
    }

    public function getCreatorId()
    {
        return $this->get("creator_id");
    }

    public function setCreatorId($value)
    {
        $this->set("creator_id", $value);
    }

    public function getCropHorizontal()
    {
        return $this->get("crop_horizontal");
    }

    public function setCropHorizontal($value)
    {
        $this->set("crop_horizontal", $value);
    }

    public function getCropType()
    {
        return $this->get("crop_type");
    }

    public function setCropType($value)
    {
        $this->set("crop_type", $value);
    }

    public function setCropVertical($value)
    {
        $this->set("crop_vertical", $value);
    }

    public function getCropVertical()
    {
        return $this->get("crop_vertical");
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

    public function setEditorId($value)
    {
        $this->set("editor_id", $value);
    }

    public function getEditorId()
    {
        return $this->get("editor_id");
    }

    public function setFeatured($value)
    {
        $this->set("featured", $value);
    }

    public function getFeatured()
    {
        return $this->get("featured");
    }

    public function setFile($value)
    {
        $this->set("file", $value);
    }

    public function getFile()
    {
        return $this->get("file");
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

    public function setTotalDownloads($value)
    {
        $this->set("total_downloads", $value);
    }

    public function getTotalDownloads()
    {
        return $this->get("total_downloads");
    }
}