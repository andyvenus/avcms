<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 11:20
 */

namespace AVCMS\Core\Model;

class ContentEntity extends Entity
{
    public function setId($value)
    {
        $this->setData('id', $value);
    }

    public function getId() {
        return $this->data('id');
    }

    public function setPublished($value)
    {
        $this->setData('published', $value);
    }

    public function getPublished() {
        return $this->data('published');
    }

    public function setDateAdded($value)
    {
        $this->setData('date_added', $value);
    }

    public function getDateAdded() {
        return $this->data('date_added');
    }

    public function setDateEdited($value)
    {
        $this->setData('date_edited', $value);
    }

    public function getDateEdited() {
        return $this->data('date_edited');
    }

    public function setCreatorId($value)
    {
        $this->setData('creator_id', $value);
    }

    public function getCreatorId() {
        return $this->data('creator_id');
    }

    public function setEditorId($value)
    {
        $this->setData('editor_id', $value);
    }

    public function getEditorId() {
        return $this->data('editor_id');
    }

    public function setSlug($value)
    {
        $this->setData('slug', $value);
    }

    public function getSlug() {
        return $this->data('slug');
    }

    public function setPublishDate($value)
    {
        $this->setData('publish_date', $value);
    }

    public function getPublishDate()
    {
        return $this->data('publish_date');
    }
} 