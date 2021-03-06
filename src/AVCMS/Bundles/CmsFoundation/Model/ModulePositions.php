<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;
use AV\Model\Model;

class ModulePositions extends Model
{
    public function getTable()
    {
        return 'module_positions';
    }

    public function getSingular()
    {
        return 'module_position';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\CmsFoundation\Model\ModulePosition';
    }

    public function disablePositionsByProvider($provider)
    {
        $this->query()->where('provider', $provider)->update(['active' => 0]);
    }

    public function getPositionsByProvider($provider)
    {
        return $this->query()->where('provider', $provider)->get();
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

    public function getFinderSortOptions()
    {
        return array(
            'a-z' => 'name asc',
            'z-a' => 'name desc',
        );
    }
}
