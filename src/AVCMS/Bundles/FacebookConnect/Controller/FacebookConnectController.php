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
use Facebook\GraphObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FacebookConnectController extends Controller
{
    public function registerAction(Request $request)
    {
        $token = $this->container->get('security.token_storage')->getToken();
        $facebookConnect = $this->container->get('facebook_connect');

        if (!$token instanceof FacebookUserToken) {
            throw $this->createNotFoundException('Not a facebook user');
        }

        if ($token->getUser()->getId() !== null) {
            throw $this->createNotFoundException('Account already registered');
        }

        $session = $facebookConnect->createSession($token->getAccessToken());
        if (!$session->validate()) {
            throw $this->createNotFoundException('Facebook session has expired, please re-login');
        }

        $users = $this->container->get('users.model');

        $form = $this->buildForm(new FacebookAccountForm(), $request);

        $facebookUser = $facebookConnect->createRequest($session, 'GET', '/me?fields=first_name,id,email')->execute()->getGraphObject(GraphObject::className())->asArray();

        if ($form->isValid()) {
            $form->saveToEntities();

            $email = isset($facebookUser['email']) ? $facebookUser['email'] : null;

            $newUser = $this->container->get('users.new_user_builder')->createNewUser($form->getData('username'), $email);

            $newUser->facebook->setId($facebookUser['id']);

            $users->save($newUser);

            $token->setUser($users->refreshUser($newUser));
            $token->setAuthenticated(true);

            return $this->redirect($this->generateUrl('home'));
        }

        $logoutUrl = $facebookConnect->getHelper()->getLogoutUrl($session, $this->generateUrl('logout', [], UrlGeneratorInterface::ABSOLUTE_URL));

        return new Response($this->render('@FacebookConnect/new_account.twig', ['form' => $form->createView(), 'facebook_user' => $facebookUser, 'logout_url' => $logoutUrl]));
    }
}
