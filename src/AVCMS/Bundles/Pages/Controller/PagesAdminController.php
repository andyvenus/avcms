<?php

namespace AVCMS\Bundles\Pages\Controller;

use AVCMS\Bundles\Pages\Form\PagesAdminFiltersForm;
use AVCMS\Bundles\Pages\Form\PageAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PagesAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Pages\Model\Pages
     */
    protected $pages;

    public function setUp(Request $request)
    {
        $this->pages = $this->model('Pages');

        if (!$this->isGranted('ADMIN_PAGES')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Pages/admin/pages_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new PageAdminForm($request->get('id', 0));

        return $this->handleEdit($request, $this->pages, $formBlueprint, 'pages_admin_edit', '@Pages/admin/edit_page.twig', '@Pages/admin/pages_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->pages->find()
            ->setSearchFields(array('title'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Pages/admin/pages_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->pages);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new PagesAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
