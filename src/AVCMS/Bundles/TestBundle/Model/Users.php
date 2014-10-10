<?php

namespace AVCMS\Bundles\TestBundle\Model;

use AVCMS\Core\Model\Model;

class Users extends Model
{
    public function getTable()
    {
        return 'users';
    }

    public function getSingular()
    {
        return 'user';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\TestBundle\Model\User';
    }
}