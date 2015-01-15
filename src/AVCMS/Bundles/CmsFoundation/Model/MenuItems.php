<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Entity;
use AV\Model\Model;

class MenuItems extends Model
{
    protected $textIdentifierColumn = 'id';

    public function getTable()
    {
        return 'menu_items';
    }

    public function getSingular()
    {
        return 'menu_item';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\CmsFoundation\Model\MenuItemConfig';
    }

    public function save(Entity $entity, $column = null)
    {
        if ($entity->getId() === null && $entity->getProviderId() !== null) {
            $column = 'provider_id';
        }

        parent::save($entity, $column);
    }
}
