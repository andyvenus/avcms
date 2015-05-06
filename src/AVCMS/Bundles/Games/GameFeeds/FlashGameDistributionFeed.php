<?php
/**
 * User: Andy
 * Date: 08/02/15
 * Time: 14:19
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\JsonResponseHandler;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class FlashGameDistributionFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://flashgamedistribution.com/feed.php?&feed=json&gpp=1000';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'name' => 'title',
            'provider_id' => 'game_id',
            'description' => 'full_description',
            'file' => 'swf_filename',
            'thumbnail' => 'thumb_filename',
            'category' => 'genres'
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
        return 'flash_game_distribution';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'Flash Game Distribution'
        ];
    }
}
