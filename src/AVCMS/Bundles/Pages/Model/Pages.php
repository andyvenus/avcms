<?php

namespace AVCMS\Bundles\Pages\Model;

use AVCMS\Core\Model\ContentModel;

class Pages extends ContentModel
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
