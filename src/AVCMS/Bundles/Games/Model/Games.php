<?php

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Model;

class Games extends Model
{
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