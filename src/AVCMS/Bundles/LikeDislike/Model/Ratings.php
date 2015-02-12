<?php

namespace AVCMS\Bundles\LikeDislike\Model;

use AV\Model\Model;

class Ratings extends Model
{
    public function getTable()
    {
        return 'ratings';
    }

    public function getSingular()
    {
        return 'Rating';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\LikeDislike\Model\Rating';
    }

    public function getLikedIds($userId, $contentType, $limit = null)
    {
        $query = $this->query()
            ->where('user_id', $userId)
            ->where('content_type', $contentType)
            ->where('rating', 1)
            ->select(['content_id'])
            ->limit($limit)
        ;

        /** @var $results Rating[] */
        $results = $query->get();

        $ids = [];
        foreach ($results as $like) {
            $ids[] = $like->getContentId();
        }

        return $ids;
    }
}
