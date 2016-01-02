<?php

namespace AVCMS\Bundles\Videos\Model;

use AVCMS\Bundles\Categories\Model\Categories;

class VideoCategories extends Categories
{
    public function getTable()
    {
        return 'video_categories';
    }

    public function getSingular()
    {
        return 'category';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Videos\Model\VideoCategory';
    }
}
