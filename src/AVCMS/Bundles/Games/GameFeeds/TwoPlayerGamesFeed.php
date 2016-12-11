<?php
/**
 * User: Andy
 * Date: 10/02/15
 * Time: 14:11
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\XmlResponseHandler;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class TwoPlayerGamesFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://old.2pg.com/games_for_your_site.xml';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'file' => 'gamecode',
            'provider_id' => 'id',
            'category' => 'tags'
        ];
    }

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData)
    {
        $game->setFile(urldecode($game->getFile()));

        return $game;
    }

    /**
     * @return ResponseHandlerInterface
     */
    public function getResponseHandler()
    {
        return new XmlResponseHandler(['gameset', 'game']);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return '2_player_games';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => '2 Player Games',
            'url' => 'http://www.2pg.com/'
        ];
    }
}
