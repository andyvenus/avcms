<?php
/**
 * User: Andy
 * Date: 10/02/15
 * Time: 11:27
 */

namespace AVCMS\Bundles\Games\GameFeeds\ResponseHandler;

class XmlResponseHandler implements ResponseHandlerInterface
{
    protected $gamesTreeStructure;

    protected $gameElement;

    public function __construct($gamesTreeStructure = ['game'])
    {
        $this->gamesTreeStructure = $gamesTreeStructure;
    }

    public function getGames($gamesXml)
    {
        foreach ($this->gamesTreeStructure as $element) {
            $gamesXml = $gamesXml->$element;
        }

        $games = [];
        foreach ($gamesXml as $game) {
            $games[] = $game;
        }

        return $games;
    }
}
