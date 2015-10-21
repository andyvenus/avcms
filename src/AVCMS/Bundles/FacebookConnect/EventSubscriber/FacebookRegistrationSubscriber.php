<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 13:54
 */

namespace AVCMS\Bundles\FacebookConnect\EventSubscriber;

use AVCMS\Bundles\FacebookConnect\Security\Token\FacebookUserToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FacebookRegistrationSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;

    private $urlGenerator;

    private $requestStack;

    public function __construct(UrlGeneratorInterface $urlGenerator, TokenStorageInterface $tokenStorage, RequestStack $requestStack)
    {
        $this->tokenStorage = $tokenStorage;
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    public function onRequest(GetResponseEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        $request = $this->requestStack->getMasterRequest();
        $route = $request->attributes->get('_route');
        if ($token instanceof FacebookUserToken && $token->getUser()->getId() === null && !in_array($route, ['facebook_register', 'login', 'register', 'logout']) && $request->isXmlHttpRequest() === false) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('facebook_register')));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onRequest', -500]
        ];
    }
}
