<?php

namespace AVCMS\Bundles\Images\Model;

use AVCMS\Core\Model\ContentModel;

class ImageCollections extends ContentModel
{
    protected $textIdentifierColumn = 'slug';

    protected $finder = 'AVCMS\Bundles\Images\Model\ImageCollectionsFinder';

    public function getTable()
    {
        return 'image_collections';
    }

    public function getSingular()
    {
        return 'image_collection';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Images\Model\ImageCollection';
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
            'liked' => ['likes desc', 'id desc'],
            'last-hit-desc' => ['last_hit desc', 'id desc'],
            'last-hit-asc' => ['last_hit asc', 'id desc'],
            'random' => 'rand() asc'
        );
    }
}
