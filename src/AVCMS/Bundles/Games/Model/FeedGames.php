<?php

namespace AVCMS\Bundles\Games\Model;

use AV\Model\Model;

class FeedGames extends Model
{
    public function getTable()
    {
        return 'feed_games';
    }

    public function getSingular()
    {
        return 'game';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Games\Model\FeedGame';
    }

    public function feedGameExists($provider, $providerId)
    {
        return $this->query()->where('provider', $provider)->where('provider_id', $providerId)->count();
    }

    public function rejectGames($ids)
    {
        $ids = (array) $ids;

        $this->query()->whereIn('id', $ids)->update(['status' => 'rejected']);
    }
}
