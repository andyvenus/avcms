<?php

namespace AVCMS\Bundles\Images\Model;

use AVCMS\Core\Model\ContentModel;

class ImageCollections extends ContentModel
{
    protected $textIdentifierColumn = 'slug';

    protected $finder = 'AVCMS\Bundles\Images\Model\ImageCollectionsFinder';

    public function getTable()
    {
        return 'images';
    }

    public function getSingular()
    {
        return 'image';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Images\Model\ImageCollection';
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
            'liked' => 'likes desc',
            'last-hit-desc' => 'last_hit desc',
            'last-hit-asc' => 'last_hit asc',
            'random' => 'rand() asc'
        );
    }
}
