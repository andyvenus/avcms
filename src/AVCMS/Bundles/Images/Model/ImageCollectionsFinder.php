<?php
/**
 * User: Andy
 * Date: 11/02/15
 * Time: 14:20
 */

namespace AVCMS\Bundles\Images\Model;

use AV\Model\Finder;

class ImageCollectionsFinder extends Finder
{
    public function featured()
    {
        $this->currentQuery->where('featured', 1);
    }

    public function category($categoryId)
    {
        if (!$categoryId) {
            return $this;
        }

        $this->currentQuery->where(function($q) use ($categoryId) {
            $q->where('category_id', $categoryId)->orWhere('category_parent_id', $categoryId);
        });

        return $this;
    }

    public function mobileOnly($mobileOnly)
    {
        if ($mobileOnly) {
            $this->currentQuery->where('file', 'NOT LIKE', '%.swf%')->where('file', 'NOT LIKE', '%.unity3d%')->where('file', 'NOT LIKE', '%.dcr%');
        }

        return $this;
    }
}
