<?php
/**
 * User: Andy
 * Date: 19/10/2016
 * Time: 11:39
 */

namespace AVCMS\Bundles\CmsFoundation\Model;

use AV\Model\Finder;

class ContentFinder extends Finder
{
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

    public function author($authorId)
    {
        if (!$authorId) {
            return $this;
        }

        $this->currentQuery->where('submitter_id', $authorId);

        return $this;
    }
}
