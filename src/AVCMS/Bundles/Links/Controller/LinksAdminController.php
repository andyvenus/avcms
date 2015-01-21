<?php

namespace AVCMS\Bundles\Links\Controller;

use AVCMS\Bundles\Links\Form\LinksAdminFiltersForm;
use AVCMS\Bundles\Links\Form\LinkAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Referrals\Form\ChoicesProvider\ReferralsChoicesProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LinksAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Links\Model\Links
     */
    protected $links;

    /**
     * @var \AVCMS\Bundles\Referrals\Model\Referrals
     */
    protected $referrals;

    public function setUp(Request $request)
    {
        $this->links = $this->model('Links');
        $this->referrals = $this->model('AVCMS\Bundles\Referrals\Model\Referrals');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Links/admin/links_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new LinkAdminForm(new ReferralsChoicesProvider($this->referrals, $this->trans('None')));

        return $this->handleEdit($request, $this->links, $formBlueprint, 'links_admin_edit', '@Links/admin/edit_link.twig', '@Links/admin/links_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->links->find()
            ->setSearchFields(array('anchor'))
            ->setResultsPerPage(15)
            //->join($this->referrals, ['id'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Links/admin/links_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->links);
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = parent::getSharedTemplateVars($ajaxDepth);

        $templateVars['finder_filters_form'] = $this->buildForm(new LinksAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
