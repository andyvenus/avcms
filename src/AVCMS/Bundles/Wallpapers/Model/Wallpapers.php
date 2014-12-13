<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AV\Model\Model;

class Wallpapers extends Model
{
    public function getTable()
    {
        return 'wallpapers';
    }

    public function getSingular()
    {
        return 'wallpaper';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Wallpapers\Model\Wallpaper';
    }
}