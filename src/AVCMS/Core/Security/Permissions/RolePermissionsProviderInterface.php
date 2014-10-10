<?php
/**
 * User: Andy
 * Date: 27/09/2014
 * Time: 10:53
 */

namespace AVCMS\Core\Security\Permissions;


interface RolePermissionsProviderInterface
{
    /**
     * @param $roleNames array|string The role name(s)
     * @return null|array
     */
    public function getRolePermissions($roleNames);
}