<?php

namespace AVCMS\Bundles\Games\Controller;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Categories\Controller\CategoryActionsTrait;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Games\Form\GamesAdminFiltersForm;
use AVCMS\Bundles\Games\Form\GameAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Games\Form\GamesCategoryAdminForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GamesAdminController extends AdminBaseController
{
    use CategoryActionsTrait;

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
        $game = $this->games->getOneOrNew($request->get('id', 0));

        $formBlueprint = new GameAdminForm($game, new CategoryChoicesProvider($this->model('GameCategories'), true, true));

        $form = $this->buildForm($formBlueprint);

        $helper = $this->editContentHelper($this->games, $form, $game);

        $helper->handleRequest($request);

        if ($helper->formValid()) {
            $helper->saveToEntities();

            $game = $helper->getEntity();
            if ($request->request->get('game_file')['file_type'] === 'embed_code') {
                $game->setFile(null);
            }
            else {
                $game->setEmbedCode('');
            }

            $helper->save(false);
        }

        if (!$helper->contentExists()) {
            throw $this->createNotFoundException('Game not found');
        }

        if (!$id = $helper->getEntity()->getId()) {
            $id = 0;
        }

        return $this->createEditResponse(
            $helper,
            '@Games/admin/edit_game.twig',
            ['games_admin_edit', ['id' => $id]]
        );
    }

    public function finderAction(Request $request)
    {
        $finder = $this->games->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->join($this->model('GameCategories'), ['id', 'name', 'slug'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null, 'category' => 0));
        $items = $finder->get();

        return new Response($this->render('@Games/admin/games_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->games);
    }

    public function toggleFeaturedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->games, 'featured');
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->games);
    }

    public function getDimensionsAction(Request $request)
    {
        $file = $request->get('file');

        if ($file === null) {
            throw $this->createNotFoundException();
        }

        $file = $this->getParam('root_dir').'/'.$this->getParam('games_dir').'/'.$file;

        $imageSize = @getimagesize($file);

        if (!$imageSize) {
            $width = 0;
            $height = 0;
        }
        else {
            $width = $imageSize[0];
            $height = $imageSize[1];
        }

        if ($width === 0 || $height === 0) {
            return new JsonResponse(['success' => false, 'error' => $this->trans('Could not get dimensions')]);
        }

        return new JsonResponse(['success' => true, 'width' => $width, 'height' => $height]);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new GamesAdminFiltersForm(new CategoryChoicesProvider($this->model('GameCategories'), true, true)))->createView();

        return $templateVars;
    }

    /**
     * @param $itemId
     * @return FormBlueprint
     */
    protected function getCategoryForm($itemId)
    {
        return new GamesCategoryAdminForm($itemId, $this->model('GameCategories'));
    }
}
