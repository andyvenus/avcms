<?php
/**
 * User: Andy
 * Date: 20/10/2016
 * Time: 20:09
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\JsonResponseHandler;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class HtmlGamesFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://www.htmlgames.com/rss/games.php?json';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'file' => 'url',
            'thumbnail' => 'thumb2'
        ];
    }

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData)
    {
        $game->setProviderId(substr(md5($feedData->name), 0, 5));

        $game->setDownloadable(0);
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
        return 'html_games';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'HTMLGames.com'
        ];
    }
}
