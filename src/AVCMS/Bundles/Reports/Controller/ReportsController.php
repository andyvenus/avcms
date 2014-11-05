<?php
/**
 * User: Andy
 * Date: 04/11/14
 * Time: 12:02
 */

namespace AVCMS\Bundles\Reports\Controller;

use AV\Form\FormError;
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
        $reportTypes = $this->container->get('report_types_manager');

        $contentType = $request->get('content_type');
        $contentId = $request->get('content_id');

        $reportForm = new ReportForm($contentType, $contentId);

        $reports = $this->model('Reports');
        $report = $reports->newEntity();

        $form = $this->buildForm($reportForm, $request, $report);

        if (!$reportTypes->contentTypeValid($contentType)) {
            $form->addCustomErrors([new FormError('content_type', 'Content type invalid')]);
        }

        $contentModel = $this->getContentModel($contentType);
        $contentEntity = $contentModel->getOne($contentId);

        if (!$contentEntity) {
            $form->addCustomErrors([new FormError('content_id', 'Content ID does not exist')]);
        }

        if ($form->isValid()) {
            $form->saveToEntities();
            $userId = $reportTypes->getUserId($contentType, $contentEntity);
            $report->setReportedUserId($userId);
            $report->setSenderUserId($this->activeUser()->getId());

            $reports->save($report);
        }

        return new JsonResponse(['success' => true, 'form' => $form->createView()->getJsonResponseData()]);
    }

    /**
     * @param $contentType
     * @return \AV\Model\Model|null
     */
    protected function getContentModel($contentType)
    {
        $typesManager = $this->container->get('report_types_manager');

        $model = null;
        if ($typesManager->contentTypeValid($contentType) === true) {
            $contentConfig = $typesManager->getContentType($contentType);
            $model = $this->model($contentConfig['model']);
        }

        return $model;
    }
} 