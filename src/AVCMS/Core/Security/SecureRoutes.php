<?php
/**
 * User: Andy
 * Date: 27/04/2014
 * Time: 15:11
 */

namespace AVCMS\Core\Security;
use AVCMS\Bundles\UsersBase\ActiveUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class SecureRoutes
 * @package AVCMS\Core\Security
 *
 * Filters routes
 */
class SecureRoutes implements EventSubscriberInterface
{
    public function __construct(ActiveUser $active_user, RouteCollection $routes)
    {
        $this->active_user = $active_user;
        $this->routes = $routes;
    }

    public function handleRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $route = $this->routes->get($request->attributes->get(('_route')));
        $route_path = $route->getPath();

        if (preg_match('/^\/admin/', $route_path)) {
            if ($this->active_user->hasPermission('admin') == false) {
                $event->setResponse($this->getNoAccessResponse());
            }
        }

        if ($request->attributes->has('_permissions')) {
            $permissions = (array) $request->attributes->get('_permissions');

            foreach ($permissions as $permission) {
                if ($this->active_user->hasPermission($permission) == false) {
                    $event->setResponse($this->getNoAccessResponse());
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('handleRequest', 8),
        );
    }

    private function getNoAccessResponse()
    {
        return new Response('You don\'t have permission to access this page.');
    }
}