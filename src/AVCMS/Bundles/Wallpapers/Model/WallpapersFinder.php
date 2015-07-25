<?php
/**
 * User: Andy
 * Date: 06/01/15
 * Time: 19:27
 */

namespace AVCMS\Bundles\Wallpapers\Model;

use AV\Model\Finder;

class WallpapersFinder extends Finder
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

    public function featured()
    {
        $this->currentQuery->where('featured', 1);
    }

    public function category($category)
    {
        if (!$category) {
            return $this;
        }

        $this->currentQuery->where(function($q) use ($category) {
            $q->where('category_id', $category->getId());

            if ($category->getChildren()) {
                $q->orWhereIn('category_id', $category->getChildren());
            }
        });

        return $this;
    }
}
