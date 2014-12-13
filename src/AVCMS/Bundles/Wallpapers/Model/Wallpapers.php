<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AVCMS\Core\Model\ContentModel;

class Wallpapers extends ContentModel
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
