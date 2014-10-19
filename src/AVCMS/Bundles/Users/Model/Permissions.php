<?php

namespace AVCMS\Bundles\Users\Model;

use AV\Model\Model;

class Permissions extends Model
{
    public function getTable()
    {
        return 'permissions';
    }

    public function getSingular()
    {
        return 'permission';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Users\Model\Permission';
    }
}