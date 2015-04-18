<?php
/**
 * User: Andy
 * Date: 10/02/15
 * Time: 13:44
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\JsonResponseHandler;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class FreeOnlineGamesFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://www.gamesforwebsites.com/feeds/games/?category=all&format=json&limit='.$settings->getSetting('game_feed_limit').'&language=en';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'instructions' => 'controls',
            'file' => 'swf_file',
            'provider_id' => 'id',
            'thumbnail' => 'med_thumbnail_url',
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
        $dimensions = explode('x', $feedData->resolution);
        $game->setWidth($dimensions[0]);
        $game->setHeight($dimensions[1]);

        if (isset($feedData->tags)) {
            $game->setTags(implode(', ', $feedData->tags));
        }
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
        return 'free_online_games';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'Free Online Games'
        ];
    }
}
