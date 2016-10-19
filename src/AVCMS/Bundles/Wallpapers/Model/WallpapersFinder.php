<?php
/**
 * User: Andy
 * Date: 06/01/15
 * Time: 19:27
 */

namespace AVCMS\Bundles\Wallpapers\Model;

use AVCMS\Bundles\CmsFoundation\Model\ContentFinder;

class WallpapersFinder extends ContentFinder
{
    public function resolution($resolution)
    {
        if (!is_string($resolution)) {
            unset($this->requestFilters['resolution']);
            return;
        }

        $dimensions = explode('x', $resolution);

        if (count($dimensions) !== 2 || !is_numeric($dimensions[0])|| !is_numeric($dimensions[1])) {
            unset($this->requestFilters['resolution']);
            return;
        }

        $this->currentQuery->where('original_width', '>=', $dimensions[0])->where('original_height', '>=', $dimensions[1]);
    }
}
