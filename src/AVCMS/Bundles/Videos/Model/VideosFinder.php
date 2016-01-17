<?php
/**
 * User: Andy
 * Date: 11/02/15
 * Time: 14:20
 */

namespace AVCMS\Bundles\Videos\Model;

use AV\Model\Finder;

class VideosFinder extends Finder
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
}
