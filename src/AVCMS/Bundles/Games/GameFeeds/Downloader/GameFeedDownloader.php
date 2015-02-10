<?php
/**
 * User: Andy
 * Date: 08/02/15
 * Time: 13:45
 */

namespace AVCMS\Bundles\Games\GameFeeds\Downloader;

use AVCMS\Bundles\Games\GameFeeds\GameFeedInterface;
use AVCMS\Bundles\Games\Model\FeedGames;
use AVCMS\Core\SettingsManager\SettingsManager;
use Curl\Curl;

class GameFeedDownloader
{
    /**
     * @var GameFeedInterface[]
     */
    private $feeds = [];

    /**
     * @var SettingsManager
     */
    private $settings;

    /**
     * @var FeedGames
     */
    private $model;

    public function __construct(SettingsManager $settings, FeedGames $model)
    {
        $this->settings = $settings;
        $this->model = $model;
    }

    public function addFeed(GameFeedInterface $gameFeed)
    {
        if (isset($this->feeds[$gameFeed->getId()])) {
            throw new \Exception('A feed already exists with the id '.$gameFeed->getId());
        }

        $this->feeds[$gameFeed->getId()] = $gameFeed;
    }

    public function getFeeds()
    {
        return $this->feeds;
    }

    public function downloadFeed($id)
    {
        if (!isset($this->feeds[$id])) {
            throw new \Exception('No feed with ID: '.$id);
        }

        $feed = $this->feeds[$id];

        $url = $feed->getFeedUrl($this->settings);

        $curl = new Curl();

        $response = $curl->get($url);

        $fields = $this->getFields();

        $parameterMap = $feed->getParameterMap();

        $gamesResponse = $feed->getResponseHandler()->getGames($response);

        foreach ($gamesResponse as $feedGame) {
            $game = $this->model->newEntity();
            foreach ($fields as $field) {
                unset($feedGameValue);

                $feedField = $field;
                if (isset($parameterMap[$field])) {
                    $feedField = $parameterMap[$field];
                }

                if (is_array($feedField)) {
                    $feedGameValue = $feedGame;
                    foreach ($feedField as $fieldName) {
                        if (isset($feedGameValue->$fieldName)) {
                            $feedGameValue = $feedGameValue->$fieldName;
                        }
                        else {
                            unset($feedGameValue);
                            break;
                        }
                    }
                }
                else {
                    if (isset($feedGame->$feedField)) {
                        $feedGameValue = $feedGame->$feedField;
                    }
                }

                if (isset($feedGameValue)) {
                    $game->{"set" . str_replace('_', '', $field)}($feedGameValue);
                }
			}

            $game->setProvider($feed->getId());

            $feed->filterGame($game, $feedGame);

            $fileType = $this->getFileType($game->getFile());

            $game->setFileType($fileType);

            if (!$this->model->feedGameExists($feed->getId(), $game->getProviderId()) && $game->getProviderId()) {
                $games[] = $game;
            }
		}

        if (isset($games)) {
            $this->model->insert($games);
            return $games;
        }

        return null;
    }

    private function getFields()
    {
        return ['name', 'description', 'file', 'thumbnail', 'category', 'width', 'height', 'instructions', 'tags', 'provider_id', 'downloadable'];
    }

    private function getFileType($file)
    {
        $fileType = strtok(pathinfo($file, PATHINFO_EXTENSION), '?');

        if (!$fileType) {
            $fileType = 'html';
        }

        $fileTypes = [
            'swf' => 'Flash',
            'unity3d' => 'Unity',
            'html' => 'HTML5',
            'htm' => 'HTML5',
            'dcr' => 'Shockwave'
        ];

        if (isset($fileTypes[$fileType])) {
            $fileType = $fileTypes[$fileType];
        }

        return $fileType;
    }
}
