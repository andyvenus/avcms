<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 13:35
 */

namespace AVCMS\Bundles\FacebookConnect\Security\AuthenticationListener;

use AVCMS\Bundles\FacebookConnect\Security\Token\FacebookUserToken;
use AVCMS\Bundles\FacebookConnect\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\GraphUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

class FacebookAuthenticationListener extends AbstractAuthenticationListener implements EventSubscriberInterface
{
    protected function attemptAuthentication(Request $request)
    {
        FacebookSession::setDefaultApplication('1535722406709073', '83145ae5b94ce97884a1e50be68f6991');

        $helper = new FacebookRedirectLoginHelper('http://localhost:8888/avcms/facebook-login-check');
        $helper->setSession($request->getSession());

        $session = $helper->getSessionFromRedirect();

        if (!$session) {
            return null;
        }

        $user = (new FacebookRequest(
            $session, 'GET', '/me?fields=id'
        ))->execute()->getGraphObject(GraphUser::className());

        $token = new FacebookUserToken($this->providerKey, $user->getId(), $session->getToken());

        return $this->authenticationManager->authenticate($token);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('handle', 100)
        );
    }
}
