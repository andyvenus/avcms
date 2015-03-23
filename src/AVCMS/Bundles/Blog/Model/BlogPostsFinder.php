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
    public function category($categoryId)
    {
        if (!$categoryId) {
            return;
        }

        $this->currentQuery->where(function($q) use ($categoryId) {
            $q->where('category_id', $categoryId)->orWhere('category_parent_id', $categoryId);
        });
    }
}
