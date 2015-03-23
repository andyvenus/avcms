<?php

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Model;

class GameSubmissions extends Model
{
    public function getTable()
    {
        return 'game_submissions';
    }

    public function getSingular()
    {
        return 'game_submission';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Games\Model\Game';
    }
}
