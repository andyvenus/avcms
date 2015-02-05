<?php

namespace AVCMS\Bundles\Wallpapers\Controller;

use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Wallpapers\Form\WallpaperAdminForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperSubmissionsAdminFiltersForm;
use AVCMS\Bundles\Wallpapers\Form\WallpaperSubmissionAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;

class WallpaperSubmissionsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Wallpapers\Model\WallpaperSubmissions
     */
    protected $wallpaperSubmissions;

    protected $browserTemplate = '@Wallpapers/admin/wallpaper_submissions_browser.twig';

    public function setUp(Request $request)
    {
        $this->wallpaperSubmissions = $this->model('WallpaperSubmissions');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Wallpapers/admin/wallpaper_submissions_browser.twig');
    }

    public function reviewAction(Request $request)
    {
        $formBlueprint = new WallpaperAdminForm($request->get('id'), new CategoryChoicesProvider($this->model('WallpaperCategories')));

        $wallpaperSubmission = $this->wallpaperSubmissions->getOne($request->get('id'));

        if (!$wallpaperSubmission) {
            throw $this->createNotFoundException('Submission not found');
        }

        $form = $this->buildForm($formBlueprint, $request, $wallpaperSubmission);

        if ($form->isSubmitted()) {
            $id = $wallpaperSubmission->getId();
            $wallpaperSubmission->setId(null);
            $request->attributes->set('_controller', 'Wallpapers::WallpapersAdminController::editAction');
            $request->attributes->set('id', null);

            $response = $this->container->get('http_kernel')->handle($request, Kernel::SUB_REQUEST);
            $json = json_decode($response->getContent());

            if ($json->form->has_errors === false) {
                $this->wallpaperSubmissions->deleteById($id);
                $json->redirect = $this->generateUrl('wallpaper_submissions_admin_home');
            }

            return new JsonResponse($json);
        }
        else {
            return new Response($this->renderAdminSection(
                '@Wallpapers/admin/review_wallpaper_submission.twig',
                ['form' => $form->createView(), 'item' => $wallpaperSubmission]
            ));
        }
    }

    public function finderAction(Request $request)
    {
        $finder = $this->wallpaperSubmissions->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->join($this->model('@users'), ['id', 'slug', 'username'], 'left', null, 'users.id', '=', 'wallpaper_submissions.submitter_id')
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Wallpapers/admin/wallpaper_submissions_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->wallpaperSubmissions);
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        $templateVars['finder_filters_form'] = $this->buildForm(new WallpaperSubmissionsAdminFiltersForm())->createView();

        return $templateVars;
    }
} 
