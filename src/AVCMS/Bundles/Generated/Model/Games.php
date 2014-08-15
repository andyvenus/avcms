<?php

namespace AVCMS\Bundles\Generated\Model;

use AVCMS\Core\Model\Model;

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
        return 'AVCMS\Bundles\Generated\Model\Game';
    }
}