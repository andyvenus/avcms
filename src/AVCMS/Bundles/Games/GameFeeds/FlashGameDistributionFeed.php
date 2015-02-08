<?php
/**
 * User: Andy
 * Date: 08/02/15
 * Time: 14:19
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\JsonResponseHandler;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Core\SettingsManager\SettingsManager;

class FlashGameDistributionFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://flashgamedistribution.com/feed.php?&feed=json&gpp='.$settings->getSetting('game_feed_limit');
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
