<?php

namespace AVCMS\Bundles\TestBundle\Controller;

use AVCMS\Bundles\TestBundle\Form\LolsAdminFiltersForm;
use AVCMS\Bundles\TestBundle\Form\LolAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LolsAdminController extends AdminBaseController
{
    protected $lols;

    public function setUp(Request $request)
    {
        $this->lols = $this->model('Lols');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@TestBundle/Lols_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new LolAdminForm();

        return $this->handleEdit($request, $this->lols, $formBlueprint, 'Lols_admin_edit', '@TestBundle/edit_Lol.twig', '@TestBundle/Lols_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->lols->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@TestBundle/Lols_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->lols);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->lols);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new LolsAdminFiltersForm())->createView();

        return $template_vars;
    }
} 