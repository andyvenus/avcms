<?php

namespace AVCMS\Bundles\Installer\Model;

use AV\Model\Model;

class Versions extends Model
{
    public function getTable()
    {
        return 'versions';
    }

    public function getSingular()
    {
        return 'version';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Installer\Model\Version';
    }
}