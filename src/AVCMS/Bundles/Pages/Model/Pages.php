<?php

namespace AVCMS\Bundles\Pages\Model;

use AV\Model\Model;

class Pages extends Model
{
    protected $textIdentifierColumn = 'slug';

    public function getTable()
    {
        return 'pages';
    }

    public function getSingular()
    {
        return 'page';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Pages\Model\Page';
    }
}
