<?php
/**
 * User: Andy
 * Date: 11/02/15
 * Time: 14:20
 */

namespace AVCMS\Bundles\Blog\Model;

use AV\Model\Finder;

class BlogPostsFinder extends Finder
{
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
