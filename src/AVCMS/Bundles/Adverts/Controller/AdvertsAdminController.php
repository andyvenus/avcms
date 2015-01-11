<?php

namespace AVCMS\Bundles\Adverts\Controller;

use AVCMS\Bundles\Adverts\Form\AdvertsAdminFiltersForm;
use AVCMS\Bundles\Adverts\Form\AdvertAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Adverts\Model\Adverts
     */
    protected $adverts;

    public function setUp(Request $request)
    {
        $this->adverts = $this->model('Adverts');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Adverts/admin/adverts_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new AdvertAdminForm();

        return $this->handleEdit($request, $this->adverts, $formBlueprint, 'adverts_admin_edit', '@Adverts/admin/edit_advert.twig', '@Adverts/admin/adverts_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->adverts->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Adverts/admin/adverts_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->adverts);
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = parent::getSharedTemplateVars($ajaxDepth);

        $templateVars['finder_filters_form'] = $this->buildForm(new AdvertsAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
