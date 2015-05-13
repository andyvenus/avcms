<?php

namespace AVCMS\Bundles\Images\Model;

use AVCMS\Bundles\Categories\Model\Categories;

class ImageCategories extends Categories
{
    public function getTable()
    {
        return 'image_categories';
    }

    public function getSingular()
    {
        return 'category';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Images\Model\ImageCategory';
    }
}
