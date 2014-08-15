<?php

namespace AVCMS\Bundles\Generated\Controller;

use AVCMS\Bundles\Generated\Form\GamesAdminFiltersForm;
use AVCMS\Bundles\Generated\Form\GameAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class GamesAdminController extends AdminController
{
    public function homeAction(Request $request)
    {
       return $this->manage($request, '@Generated/games_browser.twig');
    }

    public function editAction(Request $request)
    {
        $model = $this->model('Games');

        $form_blueprint = new GameAdminForm();

        return $this->edit($request, $model, $form_blueprint, 'games_admin_edit', '@Generated/edit_game.twig', '@Generated/games_browser.twig', array('content_name' => 'Game'));
    }

    public function finderAction(Request $request)
    {
        $model = $this->model('Games');

        $finder = $model->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Generated/games_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        $model = $this->model('Games');

        return $this->delete($request, $model);
    }

    public function togglePublishedAction(Request $request)
    {
        $model = $this->model('Games');

        return $this->togglePublished($request, $model);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new GamesAdminFiltersForm())->createView();

        return $template_vars;
    }
} 