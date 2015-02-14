<?php

namespace AVCMS\Bundles\Pages\Model;

use AVCMS\Core\Model\ContentEntity;

class Page extends ContentEntity
{
    public function setContent($value)
    {
        $this->set("content", $value);
    }

    public function getContent()
    {
        return $this->get("content");
    }

    public function getHits()
    {
        return $this->get("hits");
    }

    public function setHits($value)
    {
        $this->set("hits", $value);
    }

    public function getShowTitle()
    {
        return $this->get("show_title");
    }

    public function setShowTitle($value)
    {
        $this->set("show_title", $value);
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
