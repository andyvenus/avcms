<?php
/**
 * User: Andy
 * Date: 05/11/14
 * Time: 19:44
 */

namespace AVCMS\Bundles\Reports\Model;

use AV\Model\Finder;

class ReportsFinder extends Finder
{
    public function contentType($contentType)
    {
        if ($contentType === 'all') {
            return;
        }

        $this->currentQuery->where('content_type', $contentType);
    }
} 