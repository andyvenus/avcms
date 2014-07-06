<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:05
 */

namespace AVCMS\Bundles\Blog\Model;

use AVCMS\Core\Model\ContentModel;

class Posts extends ContentModel {
    public function getTable()
    {
        return 'blog_posts';
    }

    public function getSingular()
    {
        return 'post';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Blog\Model\Post';
    }
}