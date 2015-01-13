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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FacebookConnectController extends Controller
{
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
            $newUser->facebook->setId('10204857178562662');
            $users->save($newUser);

            $token->setUser($users->refreshUser($newUser));
            $token->setAuthenticated(true);

            return $this->redirect($this->generateUrl('home'));
        }

        return new Response($this->render('@FacebookConnect/new_account.twig', ['form' => $form->createView()]));
    }
}
