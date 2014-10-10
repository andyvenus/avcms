<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AVCMS\Core\Model\Model;
use AVCMS\Core\Module\ModuleConfigModelInterface;

class Modules extends Model implements ModuleConfigModelInterface
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

    public function getPositionModuleConfigs($position)
    {
        return $this->query()->where('position', $position)->orderBy('order')->get();
    }
}