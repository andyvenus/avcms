<?php
/**
 * User: Andy
 * Date: 10/10/2014
 * Time: 19:42
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Bundles\Users\Form\UserGroupsAdminFiltersForm;
use AVCMS\Bundles\Users\Form\UserGroupAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserGroupsAdminController extends AdminBaseController
{
    protected $userGroups;

    public function setUp(Request $request)
    {
        $this->userGroups = $this->model('UserGroups');
    }

    public function homeAction(Request $request)
    {
        return $this->handleManage($request, '@Users/user_groups_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new UserGroupAdminForm();

        return $this->handleEdit($request, $this->userGroups, $formBlueprint, 'user_groups_admin_edit', '@Users/edit_user_group.twig', '@Users/user_groups_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->userGroups->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Users/user_groups_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->userGroups);
    }

    public function togglePublishedAction(Request $request)
    {
        return $this->handleTogglePublished($request, $this->userGroups);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new UserGroupsAdminFiltersForm())->createView();

        return $template_vars;
    }
}