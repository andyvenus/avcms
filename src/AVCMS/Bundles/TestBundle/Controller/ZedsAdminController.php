<?php

namespace AVCMS\Bundles\TestBundle\Controller;

use AVCMS\Bundles\TestBundle\Form\ZedsAdminFiltersForm;
use AVCMS\Bundles\TestBundle\Form\ZedAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ZedsAdminController extends AdminBaseController
{
    protected $zeds;

    protected function setUp(Request $request)
    {
        $this->zeds = $this->model('Zeds');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@TestBundle/Zeds_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new ZedAdminForm();

        return $this->handleEdit($request, $this->zeds, $formBlueprint, 'Zeds_admin_edit', '@TestBundle/edit_Zed.twig', '@TestBundle/Zeds_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->zeds->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@TestBundle/Zeds_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->zeds);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->zeds);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new ZedsAdminFiltersForm())->createView();

        return $template_vars;
    }
} 