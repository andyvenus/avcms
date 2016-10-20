<?php
/**
 * User: Andy
 * Date: 20/10/2016
 * Time: 19:57
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\JsonResponseHandler;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class GamePixFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'https://games.gamepix.com/games';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'name' => 'title',
            'provider_id' => 'id',
            'file' => 'url',
            'thumbnail' => 'thumbnailUrl'
        ];
    }

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData)
    {
        $game->setDownloadable(0);
    }

    /**
     * @return ResponseHandlerInterface
     */
    public function getResponseHandler()
    {
        return new JsonResponseHandler('data');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'gamepix';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'GamePix'
        ];
    }
}
