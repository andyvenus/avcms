<?php
/**
 * User: Andy
 * Date: 12/02/2014
 * Time: 14:38
 */

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Model;
use AVCMS\Core\Security\Permissions\RolePermissionsProviderInterface;

class GroupPermissions extends Model implements RolePermissionsProviderInterface
{
    public function getTable()
    {
        return 'group_permissions';
    }

    public function getSingular()
    {
        return 'group_permission';
    }

    public function getEntity()
    {
        return null;
    }

    public function getGroupPermissions($group)
    {
        return $this->query()->where('group', $group)->get();
    }

    /**
     * @param $roleNames array|string The role name(s)
     * @return null|array
     */
    public function getRolePermissions($roleNames)
    {
        $roleNames = (array) $roleNames;

        if (!$roleNames) {
            return array();
        }

        $query = $this->query()->select(array('name', 'value'));

        foreach ($roleNames as $roleName) {
            $query->where('role', $roleName);
        }

        $permissions = $query->get(null, \PDO::FETCH_ASSOC);

        $filteredPermissions = array();
        foreach ($permissions as $role) {
            if (isset($filteredPermissions[$role['name']]) && $role['value'] == 1) {
                $filteredPermissions[$role['name']] = $role['value'];
            }
            elseif (!isset($filteredPermissions[$role['name']])) {
                $filteredPermissions[$role['name']] = $role['value'];
            }
        }

        return $filteredPermissions;
    }
}