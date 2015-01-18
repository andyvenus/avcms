<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Model;
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

    public function getPositionModuleConfigs($position, $includeUnpublished = true)
    {
        $q = $this->query()->where('position', $position)->orderBy('order');

        if ($includeUnpublished === false) {
            $q->where('published', 1);
        }

        return $q->get();
    }
}
