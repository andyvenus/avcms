<?php

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Model;

class Categories extends Model
{
    public function getTable()
    {
        return 'categories';
    }

    public function getSingular()
    {
        return 'category';
    }

    public function getEntity()
    {
        return 'AVCMS\Games\Model\Category';
    }
}