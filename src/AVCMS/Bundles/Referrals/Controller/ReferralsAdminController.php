<?php

namespace AVCMS\Bundles\Referrals\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Referrals\Form\ReferralAdminForm;
use AVCMS\Bundles\Referrals\Form\ReferralsAdminFiltersForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ReferralsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Referrals\Model\Referrals
     */
    protected $referrals;

    protected $browserTemplate = '@Referrals/admin/referrals_browser.twig';

    public function setUp(Request $request)
    {
        $this->referrals = $this->model('Referrals');

        if (!$this->isGranted('ADMIN_REFERRALS')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Referrals/admin/referrals_browser.twig');
    }

    public function manageAction(Request $request)
    {
        $formBlueprint = new ReferralAdminForm();

        return $this->handleEdit($request, $this->referrals, $formBlueprint, 'referrals_admin_manage', '@Referrals/admin/manage_referral.twig', array());
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

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new ReferralsAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
