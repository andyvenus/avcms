<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Model;

class Mexico extends Model
{
    public function getTable()
    {
        return 'fog';
    }

    public function getSingular()
    {
        return 'Mex';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\TestBundle\Model\Mex';
    }
}