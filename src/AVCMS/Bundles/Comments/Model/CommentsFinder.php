<?php
/**
 * User: Andy
 * Date: 30/10/14
 * Time: 23:14
 */

namespace AVCMS\Bundles\Comments\Model;

use AV\Model\Finder;

class CommentsFinder extends Finder
{
    public function contentType($contentType)
    {
        if ($contentType === 'all') {
            return;
        }

        $this->currentQuery->where('content_type', $contentType);
    }
} 