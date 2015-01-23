<?php

namespace AVCMS\Bundles\Pages\Model;

use AV\Model\Entity;

class Page extends Entity
{
    public function setContent($value)
    {
        $this->set("content", $value);
    }

    public function getContent()
    {
        return $this->get("content");
    }

    public function getCreatorId()
    {
        return $this->get("creator_id");
    }

    public function setCreatorId($value)
    {
        $this->set("creator_id", $value);
    }

    public function getDateAdded()
    {
        return $this->get("date_added");
    }

    public function setDateAdded($value)
    {
        $this->set("date_added", $value);
    }

    public function setDateEdited($value)
    {
        $this->set("date_edited", $value);
    }

    public function getDateEdited()
    {
        return $this->get("date_edited");
    }

    public function getEditorId()
    {
        return $this->get("editor_id");
    }

    public function setEditorId($value)
    {
        $this->set("editor_id", $value);
    }

    public function getHits()
    {
        return $this->get("hits");
    }

    public function setHits($value)
    {
        $this->set("hits", $value);
    }

    public function setId($value)
    {
        $this->set("id", $value);
    }

    public function getId()
    {
        return $this->get("id");
    }

    public function getPublishDate()
    {
        return $this->get("publish_date");
    }

    public function setPublishDate($value)
    {
        $this->set("publish_date", $value);
    }

    public function getPublished()
    {
        return $this->get("published");
    }

    public function setPublished($value)
    {
        $this->set("published", $value);
    }

    public function getShowTitle()
    {
        return $this->get("show_title");
    }

    public function setShowTitle($value)
    {
        $this->set("show_title", $value);
    }

    public function setSlug($value)
    {
        $this->set("slug", $value);
    }

    public function getSlug()
    {
        return $this->get("slug");
    }

    public function getTitle()
    {
        return $this->get("title");
    }

    public function setTitle($value)
    {
        $this->set("title", $value);
    }
}