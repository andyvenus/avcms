<?php

namespace AVCMS\Bundles\Generated\Controller;

use AVCMS\Bundles\Generated\Form\GroupsAdminFiltersForm;
use AVCMS\Bundles\Generated\Form\GroupAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class GroupsAdminController extends AdminController
{
    public function homeAction(Request $request)
    {
       return $this->manage($request, '@Generated/groups_browser.twig');
    }

    public function editAction(Request $request)
    {
        $model = $this->model('Groups');

        $form_blueprint = new GroupAdminForm();

        return $this->edit($request, $model, $form_blueprint, 'groups_admin_edit', '@Generated/edit_group.twig', '@Generated/groups_browser.twig', array('content_name' => 'Group'));
    }

    public function finderAction(Request $request)
    {
        $model = $this->model('Groups');

        $finder = $model->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Generated/groups_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        $model = $this->model('Groups');

        return $this->delete($request, $model);
    }

    public function togglePublishedAction(Request $request)
    {
        $model = $this->model('Groups');

        return $this->togglePublished($request, $model);
    }

    protected function getSharedTemplateVars($ajax_depth)
    {
        $template_vars = parent::getSharedTemplateVars($ajax_depth);

        $template_vars['finder_filters_form'] = $this->buildForm(new GroupsAdminFiltersForm())->createView();

        return $template_vars;
    }
} 