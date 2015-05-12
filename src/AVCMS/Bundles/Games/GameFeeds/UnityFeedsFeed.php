<?php
/**
 * User: Andy
 * Date: 10/02/15
 * Time: 14:29
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\JsonResponseHandler;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class UnityFeedsFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://unityfeeds.com/feed/?category=all&limit='.$settings->getSetting('game_feed_limit').'&format=json';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'provider_id' => 'id'
        ];
    }

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData)
    {
        $game->setTags(implode(', ', get_object_vars($feedData->tags)));
        $game->setThumbnail($feedData->thumbnails->{'300x300'});
    }

    /**
     * @return ResponseHandlerInterface
     */
    public function getResponseHandler()
    {
        return new JsonResponseHandler();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'unity_feeds';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'Unity Feeds',
            'url' => 'http://unityfeeds.com/'
        ];
    }
}
