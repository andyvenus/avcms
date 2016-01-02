<?php

namespace AVCMS\Bundles\Videos\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Videos\Form\VideoAdminForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;

class VideoSubmissionsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Videos\Model\VideoSubmissions
     */
    protected $videoSubmissions;

    protected $browserTemplate = '@Videos/admin/video_submissions_browser.twig';

    public function setUp()
    {
        $this->videoSubmissions = $this->model('VideoSubmissions');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Videos/admin/video_submissions_browser.twig');
    }

    public function reviewAction(Request $request)
    {
        $videoSubmission = $this->videoSubmissions->getOne($request->get('id'));

        $formBlueprint = new VideoAdminForm($videoSubmission, new CategoryChoicesProvider($this->model('VideoCategories')));

        if (!$videoSubmission) {
            throw $this->createNotFoundException('Submission not found');
        }

        $form = $this->buildForm($formBlueprint, $request, $videoSubmission);

        if ($form->isSubmitted()) {
            $id = $videoSubmission->getId();
            $videoSubmission->setId(null);
            $request->attributes->set('_controller', 'Videos::VideosAdminController::editAction');
            $request->attributes->set('id', null);

            $response = $this->container->get('http_kernel')->handle($request, Kernel::SUB_REQUEST);
            $json = json_decode($response->getContent());

            if ($json->form->has_errors === false) {
                $this->videoSubmissions->deleteById($id);
                $json->redirect = $this->generateUrl('video_submissions_admin_home');
            }

            return new JsonResponse($json);
        }
        else {
            return new Response($this->renderAdminSection(
                '@Videos/admin/review_video_submission.twig',
                ['form' => $form->createView(), 'item' => $videoSubmission]
            ));
        }
    }

    public function finderAction(Request $request)
    {
        $finder = $this->videoSubmissions->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->join($this->model('@users'), ['id', 'slug', 'username'], 'left', null, 'users.id', '=', 'video_submissions.submitter_id')
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Videos/admin/video_submissions_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->videoSubmissions);
    }

    public function playVideoAction(Request $request)
    {
        $video = $this->videoSubmissions->getOne($request->get('id'));

        if (!$video) {
            throw $this->createNotFoundException();
        }

        return new Response($this->renderAdminSection('@Videos/admin/watch_video_admin.twig', ['video' => $video]));
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        return $templateVars;
    }
} 
