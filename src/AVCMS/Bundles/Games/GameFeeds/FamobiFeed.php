<?php
/**
 * User: Andy
 * Date: 17/05/15
 * Time: 10:53
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\JsonResponseHandler;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class FamobiFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://api.famobi.com/feed/?n='.$settings->getSetting('game_feed_limit');
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'provider_id' => 'package_id',
            'thumbnail' => 'thumb_180',
            'file' => 'link'
        ];
    }

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData)
    {
        $game->setCategory(implode(', ', $feedData->categories));

        if (!isset($feedData->orientation)) {
            $feedData->orientation = 'portrait';
        }

        if ($feedData->orientation == 'landscape') {
            $game->setWidth('680');
            $game->setHeight('454');
        }
        else {
            $game->setWidth('389');
            $game->setHeight('600');
        }

        $game->setDownloadable(0);

    }

    /**
     * @return ResponseHandlerInterface
     */
    public function getResponseHandler()
    {
        return new JsonResponseHandler('games');
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'famobi';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'Famobi',
            'url' => 'http://famobi.com/'
        ];
    }
}
