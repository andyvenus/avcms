<?php
/**
 * User: Andy
 * Date: 25/07/2014
 * Time: 10:19
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Core\Controller\AdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersAdminController extends AdminController
{
    public function usersHomeAction(Request $request)
    {
        return $this->manage($request, $this->bundle->tpl->users_browser);
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