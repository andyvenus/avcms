<?php

namespace AVCMS\Bundles\Wallpapers\Model;

use AVCMS\Core\Model\ContentModel;

class Wallpapers extends ContentModel
{
    protected $textIdentifierColumn = 'slug';

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
            'publish-date-newest' => 'publish_date desc',
            'publish-date-oldest' => 'publish_date asc',
            'a-z' => 'name asc',
            'z-a' => 'name desc',
            'top-hits' => 'hits desc',
            'low-hits' => 'hits asc',
            'top-downloads' => 'downloads desc',
            'liked' => 'likes desc'
        );
    }
}
