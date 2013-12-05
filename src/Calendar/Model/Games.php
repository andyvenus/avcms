<?php

namespace Calendar\Model;

use AVCMS\Model\Model;
use AVCMS\Model\Entity;

class Games extends Model
{
    protected $table = 'games';

    protected $entity = 'Calendar\Model\Game';

    public function insert(Entity $entity) {
        if (isset($entity->url)) {
            $entity->filetype = substr($entity->url, strrpos($entity->url, '.') + 1);
        }

        parent::insert($entity);
    }
}