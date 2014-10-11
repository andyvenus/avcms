<?php

namespace AVCMS\Bundles\TestBundle\Controller;

use AVCMS\Bundles\TestBundle\Form\GroupsAdminFiltersForm;
use AVCMS\Bundles\TestBundle\Form\GroupAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class GroupsAdminController extends AdminBaseController
{
    protected $groups;

    public function setUp(Request $request)
    {
        $this->groups = $this->model('Groups');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@TestBundle/groups_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new GroupAdminForm();

        return $this->handleEdit($request, $this->groups, $formBlueprint, 'groups_admin_edit', '@TestBundle/edit_group.twig', '@TestBundle/groups_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->groups->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@TestBundle/groups_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->groups);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->groups);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new GroupsAdminFiltersForm())->createView();

        return $template_vars;
    }
} 