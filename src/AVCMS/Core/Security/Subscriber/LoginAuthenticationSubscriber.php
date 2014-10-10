<?php
/**
 * User: Andy
 * Date: 23/09/2014
 * Time: 13:34
 */

namespace AVCMS\Core\Security\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

class LoginAuthenticationSubscriber extends AbstractAuthenticationListener implements EventSubscriberInterface
{
    protected function attemptAuthentication(Request $request)
    {
        $username = trim($request->request->get('username', null, true));
        $password = $request->request->get('password', null, true);

        $request->getSession()->set(SecurityContextInterface::LAST_USERNAME, $username);

        $token = $unauthenticatedToken = new UsernamePasswordToken(
            $username,
            $password,
            $this->providerKey
        );

        return $this->authenticationManager->authenticate($token);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('handle', 100)
        );
    }
}