<?php
/**
 * User: Andy
 * Date: 04/11/14
 * Time: 12:02
 */

namespace AVCMS\Bundles\Reports\Controller;

use AVCMS\Bundles\Reports\Form\ReportForm;
use AVCMS\Core\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReportsController extends Controller
{
    public function reportFormAction(Request $request)
    {
        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');

        $reportForm = new ReportForm($contentType, $contentId);

        $reportForm->setAction($this->generateUrl('submit_report'));

        $form = $this->buildForm($reportForm);

        return new JsonResponse(['html' => $this->render('@Reports/report_form_modal.twig', ['form' => $form->createView()])]);
    }

    public function submitReportAction(Request $request)
    {
        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');

        $reportForm = new ReportForm($contentType, $contentId);

        $reports = $this->model('Reports');
        $report = $reports->newEntity();

        $form = $this->buildForm($reportForm, $request, $report);

        return new JsonResponse(['success' => true, 'form' => $form->createView()->getJsonResponseData()]);
    }
} 