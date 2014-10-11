<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Model;

class Groups extends Model
{
    public function getTable()
    {
        return 'groups';
    }

    public function getSingular()
    {
        return 'group';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\TestBundle\Model\Group';
    }
}