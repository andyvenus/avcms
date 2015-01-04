<?php

namespace AVCMS\Bundles\LikeDislike\Model;

use AV\Model\Model;

class Ratings extends Model
{
    public function getTable()
    {
        return 'ratings';
    }

    public function getSingular()
    {
        return 'Rating';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\LikeDislike\Model\Rating';
    }
}
