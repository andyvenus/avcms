<?php

namespace AVCMS\Bundles\Links\Model;

use AV\Model\Model;

class Links extends Model
{
    public function getTable()
    {
        return 'links';
    }

    public function getSingular()
    {
        return 'link';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Links\Model\Link';
    }
}