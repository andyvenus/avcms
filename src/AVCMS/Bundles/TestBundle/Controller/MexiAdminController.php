<?php

namespace AVCMS\Bundles\TestBundle\Controller;

use AVCMS\Bundles\TestBundle\Form\MexiAdminFiltersForm;
use AVCMS\Bundles\TestBundle\Form\MexAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MexiAdminController extends AdminBaseController
{
    protected function setUp(Request $request)
    {
        $mexi = $this->model('Mexi');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@TestBundle/Mexi_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new MexAdminForm();

        return $this->handleEdit($request, $this->mexi, $formBlueprint, 'Mexi_admin_edit', '@TestBundle/edit_Mex.twig', '@TestBundle/Mexi_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->mexi->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@TestBundle/Mexi_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->mexi);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->mexi);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new MexiAdminFiltersForm())->createView();

        return $template_vars;
    }
} 