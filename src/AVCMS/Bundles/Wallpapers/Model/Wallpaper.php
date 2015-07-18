<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AVCMS\Bundles\LikeDislike\RatingsManager\RateInterface;
use AVCMS\Core\Model\ContentEntity;

class Wallpaper extends ContentEntity implements RateInterface
{
    public function setCategoryId($value)
    {
        $this->set("category_id", $value);
    }

    public function getCategoryId()
    {
        return $this->get("category_id");
    }

    public function setComments($comments)
    {
        $this->set('comments', $comments);
    }

    public function getComments()
    {
        return $this->get('comments');
    }

    public function getCropPosition()
    {
        return $this->get("crop_position");
    }

    public function setCropPosition($value)
    {
        $this->set("crop_position", $value);
    }

    public function setResizeType($value)
    {
        $this->set("resize_type", $value);
    }

    public function getResizeType()
    {
        return $this->get("resize_type");
    }

    public function setDescription($value)
    {
        $this->set("description", $value);
    }

    public function getDescription()
    {
        return $this->get("description");
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

    public function getName()
    {
        return $this->get("name");
    }

    public function setName($value)
    {
        $this->set("name", $value);
    }

    public function getOriginalWidth()
    {
        return $this->get('original_width');
    }

    public function setOriginalWidth($width)
    {
        $this->set('original_width', $width);
    }

    public function getOriginalHeight()
    {
        return $this->get('original_height');
    }

    public function setOriginalHeight($height)
    {
        $this->set('original_height', $height);
    }

    public function getSubmitterId()
    {
        return $this->get("submitter_id");
    }

    public function setSubmitterId($value)
    {
        $this->set("submitter_id", $value);
    }

    public function getTotalDownloads()
    {
        return $this->get("total_downloads");
    }

    public function setTotalDownloads($value)
    {
        $this->set("total_downloads", $value);
    }

    public function getLastHit()
    {
        return $this->get("last_hit");
    }

    public function setLastHit($value)
    {
        $this->set("last_hit", $value);
    }

    public function getLastDownload()
    {
        return $this->get("last_download");
    }

    public function setLastDownload($value)
    {
        $this->set("last_download", $value);
    }

    public function setLikes($value)
    {
        $this->set("likes", $value);
    }

    public function getLikes()
    {
        return $this->get("likes");
    }

    public function setDislikes($value)
    {
        $this->set("dislikes", $value);
    }

    public function getDislikes()
    {
        return $this->get("dislikes");
    }
}
