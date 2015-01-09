<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AVCMS\Bundles\Categories\Model\Category;

class WallpaperCategory extends Category
{
    public function setDescription($value)
    {
        $this->set('description', $value);
    }

    public function getDescription()
    {
        return $this->get('description');
    }
}
