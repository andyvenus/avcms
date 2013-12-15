<?php

namespace Games\Model;

use AVCMS\Model\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $singular = 'category';

    protected $entity = 'Games\Model\Category';
}