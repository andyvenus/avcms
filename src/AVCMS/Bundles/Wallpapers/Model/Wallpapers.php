<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AVCMS\Core\Model\ContentModel;

class Wallpapers extends ContentModel
{
    protected $textIdentifierColumn = 'slug';

    protected $finder = 'AVCMS\Bundles\Wallpapers\Model\WallpapersFinder';

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

    public function getFinderSortOptions()
    {
        return array(
            'publish-date-newest' => ['publish_date desc', 'id desc'],
            'publish-date-oldest' => ['publish_date asc', 'id asc'],
            'a-z' => 'name asc',
            'z-a' => 'name desc',
            'top-hits' => ['hits desc', 'id desc'],
            'low-hits' => ['hits asc', 'id desc'],
            'top-downloads' => ['total_downloads desc', 'id desc'],
            'liked' => ['likes desc', 'id desc'],
            'random' => 'rand() asc'
        );
    }
}
