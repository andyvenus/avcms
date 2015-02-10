<?php
/**
 * User: Andy
 * Date: 10/02/15
 * Time: 12:28
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\XmlResponseHandler;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class SpilGamesFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://publishers.spilgames.com/en/rss-3?limit='.$settings->getSetting('game_feed_limit').'&format=xml';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'name' => 'title',
            'provider_id' => 'id',
            'file' => ['player', 'url'],
            'width' => ['player', 'width'],
            'height' => ['player', 'height'],
            'thumbnail' => ['thumbnails', 'large']
        ];
    }

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData)
    {
        $game->setThumbnail($feedData->thumbnails->large->attributes()['url']);

        if ($feedData->technology == 'iframe') {
            $game->setDownloadable(0);
        }
    }

    /**
     * @return ResponseHandlerInterface
     */
    public function getResponseHandler()
    {
        return new XmlResponseHandler(['entries', 'entry']);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'spil_games';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'Spil Games'
        ];
    }
}
