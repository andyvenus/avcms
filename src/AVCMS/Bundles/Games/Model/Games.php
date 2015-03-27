<?php

namespace AVCMS\Bundles\Games\Model;

use AVCMS\Core\Model\ContentModel;

class Games extends ContentModel
{
    protected $textIdentifierColumn = 'slug';

    protected $finder = 'AVCMS\Bundles\Games\Model\GamesFinder';

    public function getTable()
    {
        return 'games';
    }

    public function getSingular()
    {
        return 'game';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Games\Model\Game';
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
