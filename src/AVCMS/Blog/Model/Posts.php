<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:05
 */

namespace AVCMS\Blog\Model;

use AVCMS\Core\Model\Model;

class Posts extends Model {
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
        return 'AVCMS\Blog\Model\Post';
    }
}