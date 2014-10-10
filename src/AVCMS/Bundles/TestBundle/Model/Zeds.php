<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Model;

class Zeds extends Model
{
    public function getTable()
    {
        return 'fog';
    }

    public function getSingular()
    {
        return 'Zed';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\TestBundle\Model\Zed';
    }
}