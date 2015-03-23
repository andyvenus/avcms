<?php

namespace AVCMS\Bundles\Blog\Model;

use AVCMS\Bundles\Categories\Model\Categories;

class BlogCategories extends Categories
{
    public function getTable()
    {
        return 'blog_categories';
    }

    public function getSingular()
    {
        return 'category';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Blog\Model\BlogCategory';
    }
}
