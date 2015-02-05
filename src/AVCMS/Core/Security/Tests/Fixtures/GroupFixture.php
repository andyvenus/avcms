<?php
/**
 * User: Andy
 * Date: 01/12/14
 * Time: 13:15
 */

namespace AVCMS\Core\Security\Tests\Fixtures;

use AVCMS\Bundles\Users\Model\UserGroup;

class GroupFixture extends UserGroup
{
    public $adminDefault = 0;

    public $permDefault = 0;

    public $modDefault = 0;

    public function getAdminDefault()
    {
        return $this->adminDefault;
    }

    public function getPermDefault()
    {
        return $this->permDefault;
    }

    public function getModeratorDefault()
    {
        return $this->modDefault;
    }
} 
