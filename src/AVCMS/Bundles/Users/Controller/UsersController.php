<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 16:09
 */

namespace AVCMS\Bundles\Users\Controller;

use AVCMS\Core\Controller\Controller;
use AVCMS\Bundles\Users\Form\LoginForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function loginAction(Request $request)
    {
        $login_form = $this->buildForm(new LoginForm());
        $login_form->handleRequest($request);

        if ($login_form->isSubmitted() && $login_form->isValid()) {
            $login_handler = $this->activeUser()->logIn($login_form->getData('identifier'), $login_form->getData('password'), $login_form->getData('remember'));
            $login_form->addCustomErrors($login_handler->getErrors());

            if ($login_handler->loginSuccess() && $login_form->isValid()) {
                $response = new RedirectResponse($this->generateUrl('blog_home'));
                $login_handler->getLoginCookies($response);
            }
        }

        if (!isset($response)) {
            $response = new Response();
            $response->setContent($this->render('@Users/login.twig', array('login_form' => $login_form->createView())));
        }

        return $response;
    }

    public function registerAction(Request $request)
    {

    }
} 