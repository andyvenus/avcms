<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 20:26
 */

namespace AVCMS\Bundles\Games\Controller;

use AVCMS\Core\Controller\Controller;

class GamesController extends Controller
{
    /**
     * @var \AVCMS\Bundles\Games\Model\Games
     */
    private $games;

    private $gameCategories;

    public function setUp() {
        $this->games = $this->model('Games');
        $this->gameCategories = $this->model('GameCategories');
    }

    public function playGameAction($slug)
    {
        $game = $this->games->find()
            ->slug($slug)
            ->join($this->gameCategories, ['name', 'slug'])
            ->joinTaxonomy('tags')
            ->first();

        if (!$game) {
            throw $this->createNotFoundException('Game Not Found');
        }

        return $this->render('@Games/play_game.twig', ['game' => $game], true);
    }
}
