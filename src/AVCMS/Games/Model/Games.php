<?php

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Model;
use AVCMS\Core\Model\Entity;

class Games extends Model
{
    public function insert(Entity $entity) {
        if (isset($entity->url)) {
            $entity->filetype = substr($entity->url, strrpos($entity->url, '.') + 1);
        }

        parent::insert($entity);
    }

    public function getTable()
    {
        return 'games';
    }

    public function getSingular()
    {
        return 'game';
    }

    public function getEntity()
    {
        return 'AVCMS\Games\Model\Game';
    }
}