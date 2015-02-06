<?php

namespace AVCMS\Bundles\Games\Model;

use AVCMS\Core\Model\ContentModel;

class Games extends ContentModel
{
    protected $textIdentifierColumn = 'slug';

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
}
