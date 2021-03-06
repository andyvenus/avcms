<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:05
 */

namespace AVCMS\Bundles\Blog\Model;

use AVCMS\Core\Model\ContentModel;

class BlogPosts extends ContentModel
{
    protected $finder = 'AVCMS\Bundles\Blog\Model\BlogPostsFinder';

    public function getTable()
    {
        return 'blog_posts';
    }

    public function getSingular()
    {
        return 'blog_post';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Blog\Model\BlogPost';
    }

    public function getFinderSortOptions()
    {
        return array_merge(parent::getFinderSortOptions(), array(
            'user_id' => ['user_id asc', 'id desc'],
            'publish_date_newest' => ['publish_date desc', 'id desc'],
            'publish_date_oldest' => ['publish_date asc', 'id asc'],
            'a_z' => 'title asc',
            'z_a' => 'title desc',
            'top_hits' => ['hits desc', 'id desc'],
            'low_hits' => ['hits asc', 'id desc'],
        ));
    }
}
