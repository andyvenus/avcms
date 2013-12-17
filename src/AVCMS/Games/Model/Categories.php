<?php

namespace AVCMS\Games\Model;

use AVCMS\Core\Model\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $singular = 'category';

    protected $entity = 'AVCMS\Games\Model\Category';
}