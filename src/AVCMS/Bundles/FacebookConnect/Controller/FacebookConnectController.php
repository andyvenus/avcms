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

        if (!$facebookConnect->isEnabled()) {
            throw $this->createNotFoundException();
        }

        if (!$token instanceof FacebookUserToken) {
            throw $this->createNotFoundException('Not a facebook user');
        }

        if ($token->getUser()->getId() !== null) {
            throw $this->createNotFoundException('Account already registered');
        }

        $facebookConnect->setDefaultAccessToken($token->getAccessToken());

        $facebookUser = $facebookConnect->api()->get('/me?fields=first_name,id,email')->getGraphNode();

        $users = $this->container->get('users.model');

        $formBlueprint = new FacebookAccountForm(!$facebookUser->getField('email'));

        $form = $this->buildForm($formBlueprint, $request);

        if ($form->isValid()) {
            $form->saveToEntities();

            $email = $facebookUser->getField('email') ?: $form->getData('email');

            $newUser = $this->container->get('users.new_user_builder')->createNewUser($form->getData('username'), $email);

            $newUser->facebook->setId($facebookUser->getField('id'));

            $users->save($newUser);

            $token->setUser($users->refreshUser($newUser));
            $token->setAuthenticated(true);

            return $this->redirect('home');
        }

        $logoutUrl = $facebookConnect->getHelper()->getLogoutUrl($facebookConnect->api()->getDefaultAccessToken(), $this->generateUrl('logout', [], UrlGeneratorInterface::ABSOLUTE_URL));

        return new Response($this->render('@FacebookConnect/new_account.twig', ['form' => $form->createView(), 'facebook_user' => $facebookUser, 'logout_url' => $logoutUrl]));
    }
}
