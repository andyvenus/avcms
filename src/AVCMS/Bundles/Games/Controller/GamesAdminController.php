<?php

namespace AVCMS\Bundles\Games\Controller;

use AVCMS\Bundles\Games\Form\GamesAdminFiltersForm;
use AVCMS\Bundles\Games\Form\GameAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GamesAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Games\Model\Games
     */
    protected $games;

    protected $browserTemplate = '@Games/admin/games_browser.twig';

    public function setUp()
    {
        $this->games = $this->model('Games');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Games/admin/games_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new GameAdminForm($request->get('id', 0));

        return $this->handleEdit($request, $this->games, $formBlueprint, 'games_admin_edit', '@Games/admin/edit_game.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->games->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Games/admin/games_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->games);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new GamesAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
