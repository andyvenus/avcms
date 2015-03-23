<?php

namespace AVCMS\Bundles\Games\Controller;

use AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider;
use AVCMS\Bundles\Games\Form\GameAdminForm;
use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;

class GameSubmissionsAdminController extends AdminBaseController
{
    /**
     * @var \AVCMS\Bundles\Games\Model\GameSubmissions
     */
    protected $gameSubmissions;

    protected $browserTemplate = '@Games/admin/game_submissions_browser.twig';

    public function setUp()
    {
        $this->gameSubmissions = $this->model('GameSubmissions');
    }

    public function homeAction(Request $request)
    {
       return $this->handleManage($request, '@Games/admin/game_submissions_browser.twig');
    }

    public function reviewAction(Request $request)
    {
        $gameSubmission = $this->gameSubmissions->getOne($request->get('id'));

        $formBlueprint = new GameAdminForm($gameSubmission, new CategoryChoicesProvider($this->model('GameCategories')));

        if (!$gameSubmission) {
            throw $this->createNotFoundException('Submission not found');
        }

        $form = $this->buildForm($formBlueprint, $request, $gameSubmission);

        if ($form->isSubmitted()) {
            $id = $gameSubmission->getId();
            $gameSubmission->setId(null);
            $request->attributes->set('_controller', 'Games::GamesAdminController::editAction');
            $request->attributes->set('id', null);

            $response = $this->container->get('http_kernel')->handle($request, Kernel::SUB_REQUEST);
            $json = json_decode($response->getContent());

            if ($json->form->has_errors === false) {
                $this->gameSubmissions->deleteById($id);
                $json->redirect = $this->generateUrl('game_submissions_admin_home');
            }

            return new JsonResponse($json);
        }
        else {
            return new Response($this->renderAdminSection(
                '@Games/admin/review_game_submission.twig',
                ['form' => $form->createView(), 'item' => $gameSubmission]
            ));
        }
    }

    public function finderAction(Request $request)
    {
        $finder = $this->gameSubmissions->find()
            ->setSearchFields(array('name'))
            ->setResultsPerPage(15)
            ->join($this->model('@users'), ['id', 'slug', 'username'], 'left', null, 'users.id', '=', 'game_submissions.submitter_id')
            ->handleRequest($request, array('page' => 1, 'order' => 'newest', 'id' => null, 'search' => null));
        $items = $finder->get();

        return new Response($this->render('@Games/admin/game_submissions_finder.twig', array('items' => $items, 'page' => $finder->getCurrentPage())));
    }

    public function deleteAction(Request $request)
    {
        return $this->handleDelete($request, $this->gameSubmissions);
    }

    public function playGameAction(Request $request)
    {
        $game = $this->gameSubmissions->getOne($request->get('id'));

        if (!$game) {
            throw $this->createNotFoundException();
        }

        return new Response($this->renderAdminSection('@Games/admin/play_game_admin.twig', ['game' => $game]));
    }

    protected function getSharedTemplateVars()
    {
        $templateVars = parent::getSharedTemplateVars();

        return $templateVars;
    }
} 
