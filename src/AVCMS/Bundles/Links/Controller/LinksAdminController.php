<?php

namespace AVCMS\Bundles\Links\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Links\Form\LinkAdminForm;
use AVCMS\Bundles\Links\Form\LinksAdminFiltersForm;
use AVCMS\Bundles\Referrals\Form\ChoicesProvider\ReferralsChoicesProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

    protected $browserTemplate = '@Links/admin/links_browser.twig';

    public function setUp(Request $request)
    {
        $this->links = $this->model('Links');
        $this->referrals = $this->model('Referrals');

        if (!$this->isGranted('ADMIN_LINKS')) {
            throw new AccessDeniedException;
        }
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Links/admin/links_browser.twig');
    }

    public function editAction(Request $request)
    {
        $formBlueprint = new LinkAdminForm(new ReferralsChoicesProvider($this->referrals, $this->trans('None')));

        return $this->handleEdit($request, $this->links, $formBlueprint, 'links_admin_edit', '@Links/admin/edit_link.twig', array());
    }

    public function finderAction(Request $request)
    {
        $finder = $this->links->find()
            ->setSearchFields(array('anchor'))
            ->setResultsPerPage(15)
            ->join($this->referrals, ['id', 'name', 'inbound', 'outbound', 'conversions'])
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Links/admin/links_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        if ($this->checkCsrfToken($request) === false) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        if (is_array($request->request->get('ids'))) {
            $this->links->query()->whereIn('id', $request->get('ids'))->update(['admin_seen' => 1]);
        }

        return $this->handleDelete($request, $this->links);
    }

    public function togglePublishedAction(Request $request)
    {
        if ($this->checkCsrfToken($request) === false) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $seenQuery = $this->links->query();

        if (is_array($request->request->get('ids'))) {
            $seenQuery->whereIn('id', $request->get('ids'))->update(['admin_seen' => 1]);
        }
        elseif ($request->request->has('id')) {
            $seenQuery->where('id', $request->get('id'))->update(['admin_seen' => 1]);
        }

        return $this->handleTogglePublished($request, $this->links);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new LinksAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
