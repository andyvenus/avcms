<?php

namespace AVCMS\Bundles\TestBundle\Controller;

use AVCMS\Bundles\TestBundle\Form\MexicoAdminFiltersForm;
use AVCMS\Bundles\TestBundle\Form\MexAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MexicoAdminController extends AdminBaseController
{
    protected setUp(Request $request)
    {
        $mexico = $this->model('Mexico');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@TestBundle/Mexico_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new MexAdminForm();

        return $this->handleEdit($request, $this->mexico, $formBlueprint, 'Mexico_admin_edit', '@TestBundle/edit_Mex.twig', '@TestBundle/Mexico_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->mexico->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@TestBundle/Mexico_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->mexico);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->mexico);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new MexicoAdminFiltersForm())->createView();

        return $template_vars;
    }
} 