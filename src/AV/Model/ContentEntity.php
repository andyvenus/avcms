<?php
/**
 * User: Andy
 * Date: 11/06/2014
 * Time: 11:20
 */

namespace AV\Model;

class ContentEntity extends Entity
{
    public function setId($value)
    {
        $this->set('id', $value);
    }

    public function getId() {
        return $this->get('id');
    }

    public function setPublished($value)
    {
        $this->set('published', $value);
    }

    public function getPublished() {
        return $this->get('published');
    }

    public function setDateAdded($value)
    {
        $this->set('date_added', $value);
    }

    public function getDateAdded() {
        return $this->get('date_added');
    }

    public function setDateEdited($value)
    {
        $this->set('date_edited', $value);
    }

    public function getDateEdited() {
        return $this->get('date_edited');
    }

    public function setCreatorId($value)
    {
        $this->set('creator_id', $value);
    }

    public function getCreatorId() {
        return $this->get('creator_id');
    }

    public function setEditorId($value)
    {
        $this->set('editor_id', $value);
    }

    public function getEditorId() {
        return $this->get('editor_id');
    }

    public function setSlug($value)
    {
        $this->set('slug', $value);
    }

    public function getSlug() {
        return $this->get('slug');
    }

    public function setPublishDate($value)
    {
        $this->set('publish_date', $value);
    }

    public function getPublishDate()
    {
        return $this->get('publish_date');
    }
} 