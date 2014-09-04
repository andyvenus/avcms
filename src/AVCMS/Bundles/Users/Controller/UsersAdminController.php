<?php
/**
 * User: Andy
 * Date: 25/07/2014
 * Time: 10:19
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Bundles\Admin\Controller\AdminBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersAdminController extends AdminBaseController
{
    public function usersHomeAction(Request $request)
    {
        return $this->createManageResponse($request, $this->bundle->tpl->users_browser);
    }

    public function usersFinderAction(Request $request)
    {
        $users_model = $this->model('Users');

        $finder = $users_model->find();
        $users = $finder->handleRequest($request, array('page' => 1))
            ->setResultsPerPage(15);

        return new Response($this->render('@users/users_finder.twig', array('items' => $users)));
    }
}