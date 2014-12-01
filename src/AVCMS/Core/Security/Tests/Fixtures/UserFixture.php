<?php
/**
 * User: Andy
 * Date: 01/12/14
 * Time: 13:11
 */

namespace AVCMS\Core\Security\Tests\Fixtures;

use Symfony\Component\Security\Core\Role\Role;

class UserFixture
{
    /**
     * @var GroupFixture
     */
    public $group;

    /**
     * @var string
     */
    public $roleList;

    public function __construct()
    {
        $this->group = new GroupFixture();
    }

    public function getRoles()
    {
        $roles = explode(',', $this->roleList);
        $roleObjs = [];
        foreach ($roles as $role) {
            $roleObjs[] = new Role($role);
        }

        return $roleObjs;
    }

    public function getRoleList()
    {
        return $this->roleList;
    }
} 