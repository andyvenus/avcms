<?php

namespace AVCMS\Bundles\Videos\Model;

use AVCMS\Core\Model\ContentModel;

class Videos extends ContentModel
{
    protected $textIdentifierColumn = 'slug';

    protected $finder = 'AVCMS\Bundles\Videos\Model\VideosFinder';

    public function getTable()
    {
        return 'videos';
    }

    public function getSingular()
    {
        return 'video';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Videos\Model\Video';
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
