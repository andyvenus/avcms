<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 15:15
 */

namespace AVCMS\Bundles\FacebookConnect\Controller;

use AVCMS\Bundles\FacebookConnect\Form\FacebookAccountForm;
use AVCMS\Bundles\FacebookConnect\Security\Token\FacebookUserToken;
use AVCMS\Core\Controller\Controller;
use AVCMS\Bundles\FacebookConnect\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FacebookConnectController extends Controller
{
    public function loginAction()
    {
        FacebookSession::setDefaultApplication('1535722406709073', '83145ae5b94ce97884a1e50be68f6991');

        $helper = new FacebookRedirectLoginHelper($this->generateUrl('facebook_login_check', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $helper->setSession($this->container->get('session'));

        return new Response('<a href="'.$helper->getLoginUrl().'">Login</a>');
    }

    public function registerAction(Request $request)
    {
        $token = $this->container->get('security.token_storage')->getToken();

        if (!$token instanceof FacebookUserToken) {
            throw $this->createNotFoundException('Not a facebook user');
        }

        if ($token->getUser()->getId() !== null) {
            throw $this->createNotFoundException('Account already registered');
        }

        $users = $this->container->get('users.model');
        $newUser = $users->newEntity();

        $form = $this->buildForm(new FacebookAccountForm(), $request, $newUser);

        if ($form->isValid()) {
            $form->saveToEntities();
            $newUser->setSlug('testlug');
            $newUser->setFacebookId('10204857178562662');
            $users->save($newUser);

            $token->setUser($users->refreshUser($newUser));
            $token->setAuthenticated(true);

            return $this->redirect($this->generateUrl('home'));
        }

        return new Response($this->render('@FacebookConnect/new_account.twig', ['form' => $form->createView()]));
    }
}
