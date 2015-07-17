<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 13:35
 */

namespace AVCMS\Bundles\FacebookConnect\Security\AuthenticationListener;

use AVCMS\Bundles\FacebookConnect\Facebook\FacebookConnect;
use AVCMS\Bundles\FacebookConnect\Security\Token\FacebookUserToken;
use Facebook\GraphUser;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

class FacebookAuthenticationListener extends AbstractAuthenticationListener implements EventSubscriberInterface
{
    /**
     * @var FacebookConnect
     */
    protected $facebookConnect;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, SessionAuthenticationStrategyInterface $sessionStrategy, HttpUtils $httpUtils, $providerKey, AuthenticationSuccessHandlerInterface $successHandler, AuthenticationFailureHandlerInterface $failureHandler, FacebookConnect $facebookConnect, array $options = array(), LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct($tokenStorage, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, $options, $logger, $dispatcher);
        $this->facebookConnect = $facebookConnect;
    }

    protected function attemptAuthentication(Request $request)
    {
        if (!$this->facebookConnect->isEnabled()) {
            return null;
        }

        $session = $this->facebookConnect->getHelper()->getSessionFromRedirect();

        if (!$session) {
            return null;
        }

        $user = $this->facebookConnect->createRequest($session, 'GET', '/me?fields=id')->execute()->getGraphObject(GraphUser::className());

        $token = new FacebookUserToken($this->providerKey, $user->getId(), $session->getToken());

        return $this->authenticationManager->authenticate($token);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('handle', 1)
        );
    }
}
