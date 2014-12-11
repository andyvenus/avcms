<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;
use AV\Model\Model;

class Menus extends Model
{
    protected $textIdentifierColumn = 'id';

    public function getTable()
    {
        return 'menus';
    }

    public function getSingular()
    {
        return 'menu';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\CmsFoundation\Model\Menu';
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
}