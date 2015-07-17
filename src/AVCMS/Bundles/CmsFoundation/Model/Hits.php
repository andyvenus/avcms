<?php

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Model;

class Hits extends Model
{
    public function getTable()
    {
        return 'hits';
    }

    public function getSingular()
    {
        return 'hit';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\CmsFoundation\Model\Hit';
    }

    public function getHit($ip, $contentType, $contentId, $column)
    {
        $q = $this->query()
            ->where('ip', $ip)
            ->where('type', $contentType)
            ->where('content_id', $contentId)
            ->where('column', $column);

        return $q->first();
    }
}
