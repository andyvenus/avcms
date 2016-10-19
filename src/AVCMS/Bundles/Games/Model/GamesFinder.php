<?php
/**
 * User: Andy
 * Date: 11/02/15
 * Time: 14:20
 */

namespace AVCMS\Bundles\Games\Model;

use AVCMS\Bundles\CmsFoundation\Model\ContentFinder;

class GamesFinder extends ContentFinder
{
    public function mobileOnly($mobileOnly)
    {
        if ($mobileOnly) {
            $this->currentQuery->where('file', 'NOT LIKE', '%.swf%')->where('file', 'NOT LIKE', '%.unity3d%')->where('file', 'NOT LIKE', '%.dcr%');
        }

        return $this;
    }
}
