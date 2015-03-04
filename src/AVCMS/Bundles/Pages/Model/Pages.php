<?php

namespace AVCMS\Bundles\Pages\Model;

use AVCMS\Core\Model\ContentModel;

class Pages extends ContentModel
{
    protected $textIdentifierColumn = 'slug';

    public function getTable()
    {
        return 'pages';
    }

    public function getSingular()
    {
        return 'page';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Pages\Model\Page';
    }

    public function getFinderSortOptions()
    {
        return array(
            'publish-date-newest' => 'publish_date desc',
            'publish-date-oldest' => 'publish_date asc',
            'a-z' => 'title asc',
            'z-a' => 'title desc',
            'top-hits' => 'hits desc',
            'low-hits' => 'hits asc',
        );
    }
}
