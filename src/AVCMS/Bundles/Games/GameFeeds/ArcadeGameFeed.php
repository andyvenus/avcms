<?php
/**
 * User: Andy
 * Date: 10/02/15
 * Time: 14:01
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\JsonResponseHandler;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class ArcadeGameFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://arcadegamefeed.com/feed.php';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'provider_id' => 'id',
            'tags' => 'keywords',
            'name' => 'title'
        ];
    }

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData)
    {
        return $game;
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
        return 'arcade_game_feed';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'ArcadeGameFeed.com'
        ];
    }
}
