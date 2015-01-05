<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AVCMS\Bundles\Categories\Model\Categories;

class WallpaperCategories extends Categories
{
    protected $textIdentifierColumn = 'slug';

    public function getTable()
    {
        return 'wallpaper_categories';
    }

    public function getSingular()
    {
        return 'category';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Wallpapers\Model\WallpaperCategory';
    }
}
