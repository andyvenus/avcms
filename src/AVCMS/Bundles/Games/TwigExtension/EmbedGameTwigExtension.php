<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:28
 */

namespace AVCMS\Bundles\Games\TwigExtension;

use AVCMS\Bundles\Games\Model\Game;
use AVCMS\Bundles\Games\Model\GameEmbeds;

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

    public function __construct(GameEmbeds $gameEmbeds)
    {
        $this->gameEmbeds = $gameEmbeds;
    }

    public function embedGame(Game $game)
    {
        $gameEmbedTemplate = $this->gameEmbeds->getEmbedTemplate($game->getFiletype());

        if (!$gameEmbedTemplate) {
            $gameEmbedTemplate = '@Games/embeds/iframe.twig';
        }

        return $this->environment->render($gameEmbedTemplate, ['game' => $game]);
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
            )
        ];
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
}
