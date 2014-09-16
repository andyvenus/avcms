<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AVCMS\Core\Model\Model;

class ModulePositions extends Model
{
    public function getTable()
    {
        return 'module_positions';
    }

    public function getSingular()
    {
        return 'module_position';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\CmsFoundation\Model\ModulePosition';
    }
}