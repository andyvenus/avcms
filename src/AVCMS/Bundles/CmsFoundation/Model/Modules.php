<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AVCMS\Core\Model\Model;

class Modules extends Model
{
    public function getTable()
    {
        return 'modules';
    }

    public function getSingular()
    {
        return 'Module';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\CmsFoundation\Model\Module';
    }
}