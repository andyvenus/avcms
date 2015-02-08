<?php

namespace AVCMS\Bundles\Games\Controller;

use AVCMS\Bundles\Games\Form\FeedGamesAdminFiltersForm;
use AVCMS\Bundles\Games\Form\FeedGameAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FeedGamesAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Games\Model\FeedGames
     */
    protected $feedGames;

    protected $browserTemplate = '@Games/admin/feed_games_browser.twig';

    public function setUp()
    {
        $this->feedGames = $this->model('FeedGames');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Games/admin/feed_games_browser.twig');
    }

    public function downloadFeedAction()
    {
        $games = $this->container->get('game_feed_downloader')->downloadFeed('flash_game_distribution');

        return new JsonResponse(['new_games' => count($games)]);
    }

    public function finderAction(Request $request)
    {
        $finder = $this->feedGames->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        $feeds = $this->get('game_feed_downloader')->getFeeds();

        return new Response($this->render('@Games/admin/feed_games_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage(), 'feeds' => $feeds)));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->feedGames);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new FeedGamesAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
