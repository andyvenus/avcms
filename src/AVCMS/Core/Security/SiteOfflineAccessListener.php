<?php
/**
 * User: Andy
 * Date: 04/12/14
 * Time: 15:32
 */

namespace AVCMS\Core\Security;

use AVCMS\Core\Security\Exception\SiteOfflineException;
use AVCMS\Core\SettingsManager\SettingsManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Http\AccessMapInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class SiteOfflineAccessListener implements ListenerInterface
{
    private $tokenStorage;
    private $accessDecisionManager;
    private $map;
    private $authManager;
    private $settingsManager;

    public function __construct(TokenStorageInterface $tokenStorage, AccessDecisionManagerInterface $accessDecisionManager, AccessMapInterface $map, AuthenticationManagerInterface $authManager, SettingsManager $settingsManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->accessDecisionManager = $accessDecisionManager;
        $this->map = $map;
        $this->authManager = $authManager;
        $this->settingsManager = $settingsManager;
    }

    /**
     * Handles access authorization.
     *
     * @param GetResponseEvent $event A GetResponseEvent instance
     *
     * @throws SiteOfflineException
     * @throws AuthenticationCredentialsNotFoundException
     */
    public function handle(GetResponseEvent $event)
    {
        if ($this->settingsManager->getSetting('site_offline') !== '1') {
            return;
        }

        if (null === $token = $this->tokenStorage->getToken()) {
            throw new AuthenticationCredentialsNotFoundException('A Token was not found in the SecurityContext.');
        }

        $request = $event->getRequest();

        $attributes = $this->map->getPatterns($request)[0];

        if (null === $attributes) {
            return;
        }

        if (!$token->isAuthenticated()) {
            $token = $this->authManager->authenticate($token);
            $this->tokenStorage->setToken($token);
        }

        if (!$this->accessDecisionManager->decide($token, $attributes, $request)) {
            throw new SiteOfflineException();
        }
    }
}
