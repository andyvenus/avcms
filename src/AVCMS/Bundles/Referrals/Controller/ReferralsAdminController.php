<?php

namespace AVCMS\Bundles\Referrals\Controller;

use AVCMS\Bundles\Referrals\Form\ReferralsAdminFiltersForm;
use AVCMS\Bundles\Referrals\Form\ReferralAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReferralsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Referrals\Model\Referrals
     */
    protected $referrals;

    public function setUp(Request $request)
    {
        $this->referrals = $this->model('Referrals');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Referrals/admin/referrals_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new ReferralAdminForm();

        return $this->handleEdit($request, $this->referrals, $formBlueprint, 'referrals_admin_edit', '@Referrals/admin/manage_referral.twig', '@Referrals/admin/referrals_browser.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->referrals->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->join($this->model('@users'), ['id', 'slug', 'username'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Referrals/admin/referrals_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->referrals);
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = parent::getSharedTemplateVars($ajaxDepth);

        $templateVars['finder_filters_form'] = $this->buildForm(new ReferralsAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
