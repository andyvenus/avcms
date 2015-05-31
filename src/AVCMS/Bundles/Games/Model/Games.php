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
            'publish-date-newest' => ['publish_date desc', 'id desc'],
            'publish-date-oldest' => ['publish_date asc', 'id asc'],
            'a-z' => 'name asc',
            'z-a' => 'name desc',
            'top-hits' => ['hits desc', 'id desc'],
            'low-hits' => ['hits asc', 'id asc'],
            'liked' => ['likes desc', 'id desc'],
            'last-hit-desc' => ['last_hit desc', 'id desc'],
            'last-hit-asc' => ['last_hit asc', 'id asc'],
            'random' => 'rand() asc'
        );
    }
}
