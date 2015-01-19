<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AV\Model\Model;

class WallpaperSubmissions extends Model
{
    public function getTable()
    {
        return 'wallpaper_submissions';
    }

    public function getSingular()
    {
        return 'wallpaper_submission';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Wallpapers\Model\Wallpaper';
    }
}
