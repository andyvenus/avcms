<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Model;

class Lols extends Model
{
    public function getTable()
    {
        return 'fog';
    }

    public function getSingular()
    {
        return 'Lol';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\TestBundle\Model\Lol';
    }
}