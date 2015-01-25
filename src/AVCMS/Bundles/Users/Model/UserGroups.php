<?php

namespace AVCMS\Bundles\Users\Model;

use AV\Model\Entity;
use AV\Model\Model;

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

    public function save(Entity $entity, $column = null)
    {
        $id = $entity->getId();

        if ($this->query()->where('id', $id)->count() >= 1) {
            return $this->update($entity);
        }
        else {
            return $this->insert($entity);
        }
    }

    public function getJoinColumn($table = null)
    {
        if ($table === 'users') {
            return 'role_list';
        }
        
        return $this->getSingular().'_id';
    }
}
