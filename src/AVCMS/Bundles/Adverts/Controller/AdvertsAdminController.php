<?php

namespace AVCMS\Bundles\Adverts\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Adverts\Form\AdvertAdminForm;
use AVCMS\Bundles\Adverts\Form\AdvertsAdminFiltersForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdvertsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Adverts\Model\Adverts
     */
    protected $adverts;

    protected $browserTemplate = '@Adverts/admin/adverts_browser.twig';

    public function setUp()
    {
        $this->adverts = $this->model('Adverts');

        if (!$this->isGranted('ADMIN_ADVERTS')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, $this->browserTemplate);
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new AdvertAdminForm();

        return $this->handleEdit($request, $this->adverts, $formBlueprint, 'adverts_admin_edit', '@Adverts/admin/edit_advert.twig', array());
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

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new AdvertsAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
