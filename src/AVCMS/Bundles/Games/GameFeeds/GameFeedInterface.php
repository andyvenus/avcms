<?php
/**
 * User: Andy
 * Date: 08/02/15
 * Time: 13:48
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

interface GameFeedInterface {
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings);

    /**
     * @return null|array
     */
    public function getParameterMap();

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData);

    /**
     * @return ResponseHandlerInterface
     */
    public function getResponseHandler();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return array
     */
    public function getInfo();
}
