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

    public function category($category)
    {
        if (!$category) {
            return;
        }

        $this->currentQuery->where(function($q) use ($category) {
            $q->where('category_id', $category->getId());

            if ($category->getChildren()) {
                $q->orWhereIn('category_id', $category->getChildren());
            }
        });
    }

    public function mobileOnly($mobileOnly)
    {
        if ($mobileOnly) {
            $this->currentQuery->where('file', 'NOT LIKE', '%.swf%')->where('file', 'NOT LIKE', '%.unity3d%')->where('file', 'NOT LIKE', '%.dcr%');
        }

        return $this;
    }
}
