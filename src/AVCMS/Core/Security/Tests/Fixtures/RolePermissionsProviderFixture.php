<?php
/**
 * User: Andy
 * Date: 01/12/14
 * Time: 13:24
 */

namespace AVCMS\Core\Security\Tests\Fixtures;

use AVCMS\Core\Security\Permissions\RolePermissionsProviderInterface;

class RolePermissionsProviderFixture implements RolePermissionsProviderInterface
{
    public $permissions;

    public function getRolePermissions($roleName)
    {
        return (isset($this->permissions[$roleName[0]]) ? $this->permissions[$roleName[0]] : null);
    }
}