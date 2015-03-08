<?php
/**
 * User: Andy
 * Date: 10/02/15
 * Time: 11:38
 */

namespace AVCMS\Bundles\Games\GameFeeds;

use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\ResponseHandlerInterface;
use AVCMS\Bundles\Games\GameFeeds\ResponseHandler\XmlResponseHandler;
use AVCMS\Bundles\Games\Model\FeedGame;
use AVCMS\Core\SettingsManager\SettingsManager;

class KongregateFeed implements GameFeedInterface
{
    /**
     * @param $settings
     * @return string
     */
    public function getFeedUrl(SettingsManager $settings)
    {
        return 'http://www.kongregate.com/games_for_your_site.xml';
    }

    /**
     * @return null|array
     */
    public function getParameterMap()
    {
        return [
            'provider_id' => 'id',
            'name' => 'title',
            'file' => 'flash_file',
        ];
    }

    /**
     * @param FeedGame $game
     * @param $feedData
     * @return mixed
     */
    public function filterGame(FeedGame $game, $feedData)
    {
        $game->setFile(str_replace('external.kongregate-games.com', 'chat.kongregate.com', $game->getFile()));

        $game->setDescription(trim($this->convertSmartQuotes($game->getDescription())));
        $game->setInstructions(trim($this->convertSmartQuotes($game->getInstructions())));

        return $game;
    }

    /**
     * @return ResponseHandlerInterface
     */
    public function getResponseHandler()
    {
        return new XmlResponseHandler();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'kongregate';
    }

    /**
     * @return array
     */
    public function getInfo()
    {
        return [
            'name' => 'Kongregate'
        ];
    }

    private function convertSmartQuotes($string)
    {
        $search = array('&#8216;',
            '&#8217;',
            '&#8221;',
            '&#8220;',
            '&#8212;');

        $replace = array("'",
            "'",
            '"',
            '"',
            '-');

        return str_replace($search, $replace, $string);
    }
}
