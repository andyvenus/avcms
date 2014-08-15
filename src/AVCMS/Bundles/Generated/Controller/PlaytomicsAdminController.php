<?php

namespace AVCMS\Bundles\Generated\Controller;

use AVCMS\Bundles\Generated\Form\PlaytomicsAdminFiltersForm;
use AVCMS\Bundles\Generated\Form\PlaytomicAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PlaytomicsAdminController extends AdminController
{
    public function homeAction(Request $request)
    {
       return $this->manage($request, '@Generated/playtomics_browser.twig');
    }

    public function editAction(Request $request)
    {
        $model = $this->model('Playtomics');

        $form_blueprint = new PlaytomicAdminForm();

        return $this->edit($request, $model, $form_blueprint, 'playtomics_admin_edit', '@Generated/edit_playtomic.twig', '@Generated/playtomics_browser.twig', array('content_name' => 'Playtomic'));
    }

    public function finderAction(Request $request)
    {
        $model = $this->model('Playtomics');

        $finder = $model->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Generated/playtomics_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        $model = $this->model('Playtomics');

        return $this->delete($request, $model);
    }

    public function togglePublishedAction(Request $request)
    {
        $model = $this->model('Playtomics');

        return $this->togglePublished($request, $model);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new PlaytomicsAdminFiltersForm())->createView();

        return $template_vars;
    }
} 