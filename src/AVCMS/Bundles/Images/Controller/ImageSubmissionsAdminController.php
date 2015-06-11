<?php

namespace AVCMS\Bundles\Images\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Images\Form\ImageAdminForm;
use AVCMS\Bundles\Images\Form\ImageFilesAdminForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;

class ImageSubmissionsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Images\Model\ImageSubmissions
     */
    protected $imageSubmissions;

    /**
     * @var \AVCMS\Bundles\Images\Model\ImageSubmissionFiles
     */
    protected $imageSubmissionFiles;

    protected $browserTemplate = '@Images/admin/image_submissions_browser.twig';

    public function setUp()
    {
        $this->imageSubmissions = $this->model('ImageSubmissions');
        $this->imageSubmissionFiles = $this->model('ImageSubmissionFiles');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Images/admin/image_submissions_browser.twig');
    }

    public function reviewAction(Request $request)
    {
        $imageSubmission = $this->imageSubmissions->getOne($request->get('id'));

        $formBlueprint = new ImageAdminForm($imageSubmission, new CategoryChoicesProvider($this->model('ImageCategories')));
        $formBlueprint->setName('review_submitted_image_form');

        if (!$imageSubmission) {
            throw $this->createNotFoundException('Submission not found');
        }

        /**
         * @var $files \AVCMS\Bundles\Images\Model\ImageFile[]
         */
        $files = $this->imageSubmissionFiles->getImageFiles($imageSubmission->getId());
        $filesForm = $this->buildForm(new ImageFilesAdminForm($files));

        foreach ($files as $file) {
            $filesForm->mergeData(['images' => [$file->getId() => ['file' => $file->getUrl(), 'caption' => $file->getCaption()]]]);
        }

        $form = $this->buildForm($formBlueprint, $request, $imageSubmission);

        if ($form->isSubmitted()) {
            $id = $imageSubmission->getId();
            $imageSubmission->setId(null);
            $request->attributes->set('_controller', 'Images::ImagesAdminController::editAction');
            $request->attributes->set('id', null);

            $response = $this->container->get('http_kernel')->handle($request, Kernel::SUB_REQUEST);
            $json = json_decode($response->getContent());

            if ($json->form->has_errors === false) {
                $this->imageSubmissions->deleteById($id);
                $this->imageSubmissionFiles->query()->where('image_id', $id)->delete();
                $json->redirect = $this->generateUrl('image_submissions_admin_home');
            }

            return new JsonResponse($json);
        }
        else {
            return new Response($this->renderAdminSection(
                '@Images/admin/review_image_submission.twig',
                ['form' => $form->createView(), 'item' => $imageSubmission, 'files_form' => $filesForm->createView(), 'files' => $files]
            ));
        }
    }

    public function finderAction(Request $request)
    {
        $finder = $this->imageSubmissions->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->join($this->model('@users'), ['id', 'slug', 'username'], 'left', null, 'users.id', '=', 'image_submissions.submitter_id')
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Images/admin/image_submissions_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        if (!$this->checkCsrfToken($request)) {
            return $this->invalidCsrfTokenJsonResponse();
        }

        $ids = $request->request->get('ids');

        $filesQuery = $this->imageSubmissionFiles->query()->whereIn('image_id', (array) $ids);
        $files = $filesQuery->get();

        foreach ($files as $file) {
            $filePath = $this->getParam('images_dir').'/'.$file->getUrl();

            unlink($filePath);

            $filesDirectory = dirname($filePath);
        }

        if (isset($filesDirectory) && count(glob($filesDirectory."/*")) === 0 ) {
            rmdir($filesDirectory);
        }

        $filesQuery->delete();

        return $this->handleDelete($request, $this->imageSubmissions);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        return $templateVars;
    }
} 
