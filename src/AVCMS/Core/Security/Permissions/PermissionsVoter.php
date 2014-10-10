<?php
/**
 * User: Andy
 * Date: 27/09/2014
 * Time: 10:39
 */

namespace AVCMS\Core\Security\Permissions;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class PermissionsVoter implements VoterInterface
{
    protected $permissionsModel;

    protected $prefix;

    protected $permissions;

    public function __construct(RolePermissionsProviderInterface $permissionsModel, $prefix = 'PERM_')
    {
        $this->permissionsModel = $permissionsModel;
        $this->prefix = $prefix;
    }

    public function supportsAttribute($attribute)
    {
        return 0 === strpos($attribute, $this->prefix);
    }

    public function supportsClass($class)
    {
        return false;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $result = VoterInterface::ACCESS_DENIED;
            if ($this->hasPermission($attribute, $token)) {
                return VoterInterface::ACCESS_GRANTED;
            }

        }

        return $result;
    }

    private function hasPermission($attribute, TokenInterface $token)
    {
        $roles = $token->getUser()->getRoles();

        // Admins always have permission
        foreach ($roles as $role) {
            if ($role->getRole() == 'ROLE_ADMIN') {
                return true;
            }
        }

        if (!isset($this->permissions[ $token->getUser()->getRoleList() ])) {
            $this->permissions[ $token->getUser()->getRoleList() ] = $this->getPermissions($roles);
        }

        $perms =& $this->permissions[ $token->getUser()->getRoleList() ];

        return (isset($perms[$attribute]) ? $perms[$attribute] : false);
    }

    private function getPermissions(array $roles)
    {
        $roleNames = array();
        foreach ($roles as $role) {
            $roleNames[] = $role->getRole();
        }

        return $this->permissionsModel->getRolePermissions($roleNames);
    }
}