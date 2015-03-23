<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:39
 */

namespace AVCMS\Bundles\Blog\Model;

use AVCMS\Core\Model\ContentEntity;

class BlogPost extends ContentEntity {
    public function setTitle($value) {
        $this->set('title', $value);
    }

    public function getTitle() {
        return $this->get('title');
    }

    public function setBody($value) {
        $this->set('body', $value);
    }

    public function getBody() {
        return $this->get('body');
    }

    public function setUserId($value) {
        $this->set('user_id', $value);
    }

    public function getUserId() {
        return $this->get('user_id');
    }

    public function setComments($comments)
    {
        $this->set('comments', $comments);
    }

    public function getComments()
    {
        return $this->get('comments');
    }

    public function setHits($hits)
    {
        $this->set('hits', $hits);
    }

    public function getHits()
    {
        return $this->get('hits');
    }

    public function setCategoryId($categoryId)
    {
        $this->set('category_id', $categoryId);
    }

    public function getCategoryId()
    {
        return $this->get('category_id');
    }

    public function setCategoryParentId($categoryParentId)
    {
        $this->set('category_parent_id', $categoryParentId);
    }

    public function getCategoryParentId()
    {
        return $this->get('category_parent_id');
    }
} 
