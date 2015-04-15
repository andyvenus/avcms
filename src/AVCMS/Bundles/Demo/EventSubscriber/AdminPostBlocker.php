<?php
/**
 * User: Andy
 * Date: 15/04/15
 * Time: 13:29
 */

namespace AVCMS\Bundles\Demo\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AdminPostBlocker implements EventSubscriberInterface
{
    private $adminRequestMatcher;

    public function __construct(RequestMatcher $adminRequestMatcher)
    {
        $this->adminRequestMatcher = $adminRequestMatcher;
    }

    public function blockPostRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$this->adminRequestMatcher->matches($request) && !in_array($request->get('_route'), ['edit_user_profile', 'user_email_password'])) {
            return;
        }

        if ($request->getMethod() !== 'GET') {
            if ($request->isXmlHttpRequest()) {
                $event->setResponse(new JsonResponse([
                    'success' => false,
                    'error' => 'Changes cannot be saved in the demo',
                    'form' => [
                        'has_errors' => true,
                        'errors' => [
                            ['param' => 'a', 'message' => 'Changes cannot be saved in the demo']
                        ]
                    ]
                ]));
            }
            else {
                $event->setResponse(new Response('Sorry this can\'t be done in the demo'));
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['blockPostRequest']
        ];
    }
}
