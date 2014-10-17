<?php

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Model;

class UserGroups extends Model
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
        return 'AVCMS\Bundles\Users\Model\UserGroup';
    }
}