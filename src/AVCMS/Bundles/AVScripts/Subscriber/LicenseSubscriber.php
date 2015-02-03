<?php
/**
 * User: Andy
 * Date: 03/02/15
 * Time: 12:07
 */

namespace AVCMS\Bundles\AVScripts\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class LicenseSubscriber implements EventSubscriberInterface
{
    protected $appConfig;

    protected $requestMatcher;

    protected $rootDir;

    protected $authorizationChecker;

    protected $fragmentHandler;

    protected $controller;

    public function __construct(RequestMatcher $requestMatcher, $rootDir, AuthorizationCheckerInterface $authorizationChecker, FragmentHandler $fragmentHandler, $appConfig, $controller = 'AVCMS\Bundles\AVScripts\Controller\LicenseController::licenseKeyAction')
    {
        $this->requestMatcher = $requestMatcher;
        $this->rootDir = $rootDir;
        $this->appConfig = $appConfig;
        $this->fragmentHandler = $fragmentHandler;
        $this->authorizationChecker = $authorizationChecker;
        $this->controller = $controller;
    }

    public function checkLicense(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$this->requestMatcher->matches($request)) {
            return;
        }

        if (!$this->authorizationChecker->isGranted('ADMIN_ACCESS')) {
            return;
        }

        if (!isset($this->appConfig['license'])) {
            return;
        }

        $licenseFile = $this->rootDir.'/webmaster/license.php';

        if (file_exists($licenseFile)) {
            $license = include $licenseFile;

            if ($this->checkKey($license)) {
                return;
            }
        }

        // The license file doesn't exist, show a form
        $event->setResponse(new Response($this->fragmentHandler->render(new ControllerReference($this->controller))));
    }

    public function checkKey($license)
    {
        if (!isset($this->appConfig['license'])) {
            return true;
        }

        $exploded = explode('-', $license);
        if (count($exploded) !== 2) {
            return false;
        }

        if ($exploded[0] === $this->appConfig['license']['prefix'] && strlen($exploded[1]) == $this->appConfig['license']['length']) {
            return true;
        }

        return false;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['checkLicense', -100]
        ];
    }
}
