<?php
/**
 * User: Andy
 * Date: 12/02/2014
 * Time: 14:38
 */

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Model;

class GroupPermissions extends Model
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
        return 'AVCMS\Bundles\Users\Model\GroupPermission';
    }

    public function getGroupPermissions($group)
    {
        return $this->query()->where('group', $group)->get();
    }
}