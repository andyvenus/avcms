<?php
/**
 * User: Andy
 * Date: 09/02/15
 * Time: 18:26
 */

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Model;

class GameFeedCategories extends Model
{
    public function getTable()
    {
        return 'game_feed_categories';
    }

    public function getSingular()
    {
        return 'game_feed_category';
    }

    public function getEntity()
    {
        return null;
    }

    public function getCategoryId($keywords)
    {
        if (is_string($keywords)) {
            $keywords = explode(',', $keywords);
        }

        $result = $this->query()->whereIn('keyword', $keywords)->select(['category_id'])->first(\PDO::FETCH_ASSOC);

        return $result ? $result['category_id'] : 0;
    }

    public function setKeywords($categoryId, $keywords)
    {
        if (is_string($keywords)) {
            $keywords = explode(',', $keywords);
        }

        $this->query()->where('category_id', $categoryId)->delete();

        foreach ($keywords as $keyword) {
            $this->query()->insert(['keyword' => trim($keyword), 'category_id' => $categoryId]);
        }
    }

    public function getKeywords($categoryId)
    {
        $keywords = [];
        foreach ($this->query()->where('category_id', $categoryId)->get(null, \PDO::FETCH_ASSOC) as $row) {
            $keywords[] = $row['keyword'];
        }

        return $keywords;
    }
}
