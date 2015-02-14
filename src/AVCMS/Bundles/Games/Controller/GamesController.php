<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 20:26
 */

namespace AVCMS\Bundles\Games\Controller;

use AVCMS\Bundles\Games\Form\GameFrontendFiltersForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            ->published()
            ->slug($slug)
            ->join($this->gameCategories, ['name', 'slug'])
            ->joinTaxonomy('tags')
            ->first();

        if (!$game) {
            throw $this->createNotFoundException('Game Not Found');
        }

        return $this->render('@Games/play_game.twig', ['game' => $game], true);
    }

    public function browseGamesAction(Request $request, $pageType = 'archive')
    {
        $finder = $this->games->find();
        $query = $finder->published()
            ->setResultsPerPage($this->setting('browse_games_per_page'))
            ->setSearchFields(['name'])
            ->handleRequest($request, [
                'page' => 1,
                'order' => 'publish-date-newest',
                'tags' => null,
                'search' => null,
            ]);

        if ($this->setting('show_game_category')) {
            $query->join($this->gameCategories, ['name', 'slug']);
        }

        $category = null;
        if ($request->get('category') !== null) {
            $category = $this->gameCategories->getFullCategory($request->get('category'));

            $query->category($category->getId());
        }

        if ($pageType === 'featured') {
            $query->featured();
        }

        $games = $query->get();

        $formBp = new GameFrontendFiltersForm();
        $attr = $request->attributes->all();
        $attr['page'] = 1;
        $formBp->setAction($this->generateUrl($request->attributes->get('_route'), $attr));

        $filtersForm = $this->buildForm($formBp, $request);

        return new Response($this->render('@Games/browse_games.twig', array(
            'games' => $games,
            'total_pages' => $finder->getTotalPages(),
            'current_page' => $finder->getCurrentPage(),
            'page_type' => $pageType,
            'category' => $category,
            'filters_form' => $filtersForm->createView(),
            'finder_request' => $finder->getRequestFilters(),
            'user_settings' => $this->get('settings_manager')
        )));
    }
}
