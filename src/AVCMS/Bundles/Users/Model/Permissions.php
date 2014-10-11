<?php

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Model;

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