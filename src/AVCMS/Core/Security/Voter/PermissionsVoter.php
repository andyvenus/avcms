<?php
/**
 * User: Andy
 * Date: 27/09/2014
 * Time: 10:39
 */

namespace AVCMS\Core\Security\Voter;

use AVCMS\Core\Security\Permissions\RolePermissionsProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class PermissionsVoter implements VoterInterface
{
    protected $permissionsModel;

    protected $prefix;

    protected $adminPrefix;

    protected $moderatorPrefix;

    protected $elevatedPrefix;

    protected $permissions;

    public function __construct(RolePermissionsProviderInterface $permissionsModel, $prefix = 'PERM_', $adminPrefix = 'ADMIN_', $moderatorPrefix = 'MODERATOR_', $elevatedPrefix = 'ELEVATED_')
    {
        $this->permissionsModel = $permissionsModel;
        $this->prefix = $prefix;
        $this->adminPrefix = $adminPrefix;
        $this->moderatorPrefix = $moderatorPrefix;
        $this->elevatedPrefix = $elevatedPrefix;
    }

    public function supportsAttribute($attribute)
    {
        return (0 === strpos($attribute, $this->prefix) || 0 === strpos($attribute, $this->adminPrefix) || 0 === strpos($attribute, $this->moderatorPrefix) || 0 === strpos($attribute, $this->elevatedPrefix));
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
            if ($this->hasPermission($attribute, $token) === true) {
                return VoterInterface::ACCESS_GRANTED;
            }

        }

        return $result;
    }

    private function hasPermission($attribute, TokenInterface $token)
    {
        $roles = $token->getUser()->getRoles();
        $group = $token->getUser()->group;

        if (strpos($attribute, $this->adminPrefix) === 0) {
            if ($group->getAdminPanelAccess() !== '1') {
                return false;
            }
        }

        foreach ($roles as $role) {
            // Super admins always have permission
            if ($role->getRole() == 'ROLE_SUPER_ADMIN') {
                return true;
            }
            // Banned users are always denied
            elseif ($role->getRole() == 'ROLE_BANNED') {
                return false;
            }
        }

        if (!isset($this->permissions[ $token->getUser()->getRoleList() ])) {
            $this->permissions[ $token->getUser()->getRoleList() ] = $this->getPermissions($roles);
        }

        $perms =& $this->permissions[ $token->getUser()->getRoleList() ];

        if (!isset($perms[$attribute])) {
            $permType = explode('_', $attribute)[0];
            if (is_callable([$group, "get{$permType}default"])) {
                $permission = ($group->{"get{$permType}default"}() === 'allow' ? true : false);
            }
            else {
                $permission = false;
            }
        }
        else {
            $permission = (bool) $perms[$attribute];
        }

        return $permission;
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
