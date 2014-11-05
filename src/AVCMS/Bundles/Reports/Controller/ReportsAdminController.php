<?php
/**
 * User: Andy
 * Date: 05/11/14
 * Time: 11:46
 */

namespace AVCMS\Bundles\Reports\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Reports\Form\ReportFiltersForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ReportsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Reports\Model\Reports
     */
    private $reports;

    /**
     * @var \AVCMS\Bundles\Reports\ReportTypesManager
     */
    private $reportTypes;

    public function setUp()
    {
        if (!$this->isGranted('ADMIN_REPORTS')) {
            throw new AccessDeniedException;
        }

        $this->reports = $this->model('Reports');
        $this->reportTypes = $this->container->get('report_types_manager');
    }

    public function manageReportsAction(Request $request)
    {
        return $this->handleManage($request, '@Reports/reports_browser.twig');
    }

    public function reportsFinderAction(Request $request)
    {
        $reports = $this->reports->find()
            ->join($this->model('@users'), ['username', 'slug'], 'left', null, 'reported_user.id', '=', 'reports.reported_user_id', 'reported_user')
            ->join($this->model('@users'), ['username', 'slug'], 'left', null, 'sender_user.id', '=', 'reports.sender_user_id', 'sender_user')
            ->setResultsPerPage(10)
            ->setSearchFields(['message'])
            ->handleRequest($request, ['page' => 1, 'order' => 'newest', 'search' => null, 'contentType' => 'all'])
            ->get();

        foreach ($reports as $report) {
            $type = $report->getContentType();

            if ($this->reportTypes->contentTypeValid($type) === false) {
                continue;
            }

            $typeConfig = $this->reportTypes->getContentType($type);

            $contentModel = $this->model($typeConfig['model']);
            $content = $contentModel->getOne($report->getContentId());

            if (!$content) {
                continue;
            }

            $contentInfo = [];

            if ($typeConfig['route'] !== null) {
                $contentInfo['title'] = $typeConfig['name'];
                $frontendRoute = $typeConfig['route'];
                $params = $typeConfig['route_params'];
            }

            $titleField = $typeConfig['title_field'];

            if ($titleField !== null && is_callable([$content, "get$titleField"])) {
                $contentInfo['title'] = $content->{"get$titleField"}();
            }

            $contentField = $typeConfig['content_field'];

            if ($contentField !== null && is_callable([$content, "get$contentField"])) {
                $contentInfo['content'] = $content->{"get$contentField"}();
            }

            if (!isset($frontendRoute) || !$frontendRoute) {
                $contentInfo['url'] = null;
            }
            elseif (empty($params)) {
                $contentInfo['url'] = $this->generateUrl($frontendRoute, UrlGeneratorInterface::ABSOLUTE_URL);
            }
            else {
                foreach ($params as $paramName) {
                    if (!is_callable([$content, "get$paramName"])) {
                        unset($urlParams);
                        continue;
                    }
                    $urlParams[$paramName] = $content->{"get$paramName"}();
                }

                if (isset($urlParams)) {
                    $contentInfo['url'] = $this->generateUrl($frontendRoute, $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);
                }
                else {
                    $contentInfo['url'] = null;
                }
            }

            $report->content = $contentInfo;
        }

        return new Response($this->render('@Reports/reports_finder.twig', ['reports' => $reports]));
    }

    public function deleteReportsAction(Request $request)
    {
        return $this->handleDelete($request, $this->reports);
    }

    protected function getSharedTemplateVars($ajaxDepth)
    {
        $templateVars = parent::getSharedTemplateVars($ajaxDepth);

        $contentTypes = $this->reportTypes->getContentTypes();

        $contentTypesSelect['all'] = 'All';
        foreach ($contentTypes as $contentTypeName => $contentType) {
            $contentTypesSelect[$contentTypeName] = $contentType['name'];
        }

        $templateVars['finder_filters_form'] = $this->buildForm(new ReportFiltersForm($contentTypesSelect))->createView();

        return $templateVars;
    }
} 