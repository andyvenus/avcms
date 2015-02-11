<?php
/**
 * User: Andy
 * Date: 11/02/15
 * Time: 14:20
 */

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Finder;

class GamesFinder extends Finder
{
    public function featured()
    {
        $this->currentQuery->where('featured', 1);
    }

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
