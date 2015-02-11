<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:28
 */

namespace AVCMS\Bundles\Games\TwigExtension;

use AVCMS\Bundles\Games\Model\Game;
use AVCMS\Bundles\Games\Model\GameEmbeds;
use AVCMS\Core\Kernel\SiteRootUrl;

class EmbedGameTwigExtension extends \Twig_Extension
{
    /**
     * @var GameEmbeds
     */
    private $gameEmbeds;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    private $gamesPath;

    private $rootUrl;

    private $thumbnailsPath;

    public function __construct(GameEmbeds $gameEmbeds, $rootUrl, $gamesPath, $thumbnailsPath)
    {
        $this->gameEmbeds = $gameEmbeds;
        $this->rootUrl = $rootUrl;
        $this->gamesPath = $gamesPath;
        $this->thumbnailsPath = $thumbnailsPath;
    }

    public function gameThumbnailUrl(Game $game)
    {
        $thumbnail = $game->getThumbnail();

        if (strpos($thumbnail, '://') === false) {
            return $this->rootUrl.'/'.$this->thumbnailsPath.'/'.$thumbnail;
        }

        return $thumbnail;
    }

    public function embedGame(Game $game)
    {
        $this->setRealGameUrl($game);

        if (!$game->getFile()) {
            $fileExtension = 'html_embed';
        }
        else {
            $fileExtension = pathinfo(strtok($game->getFile(),  '?'), PATHINFO_EXTENSION);
        }

        $gameEmbedTemplate = $this->gameEmbeds->getEmbedTemplate($fileExtension);

        if (!$gameEmbedTemplate) {
            $gameEmbedTemplate = '@Games/embeds/iframe.twig';
        }

        return $this->environment->render($gameEmbedTemplate, ['game' => $game]);
    }

    private function setRealGameUrl(Game $game)
    {
        $url = $game->getFile();

        if (strpos($url, '://') === false) {
            $game->setFile($this->rootUrl.$this->gamesPath.'/'.$url);
        }
    }

    public function getName()
    {
        return 'avcms_embed';
    }

    public function getFunctions()
    {
        return [
            'embed_game' => new \Twig_SimpleFunction(
                'embed_game',
                array($this, 'embedGame'),
                array('is_safe' => array('html'))
            ),
            'game_thumbnail' => new \Twig_SimpleFunction(
                'game_thumbnail',
                array($this, 'gameThumbnailUrl'),
                array('is_safe' => array('html'))
            )
        ];
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
}
