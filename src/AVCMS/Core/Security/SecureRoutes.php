<?php
/**
 * User: Andy
 * Date: 27/04/2014
 * Time: 15:11
 */

namespace AVCMS\Core\Security;
use AVCMS\Core\Security\User\ActiveUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class SecureRoutes
 * @package AVCMS\Core\Security
 *
 * Checks routes to see if they're protected by a permission and validates
 * the active user's permission to view the page
 */
class SecureRoutes implements EventSubscriberInterface
{
    /**
     * @var array
     */
    protected $secureUrlRoutes = array();

    public function __construct(ActiveUser $activeUser)
    {
        $this->activeUser = $activeUser;
    }

    public function addRouteMatcherPermission($regex, $permission)
    {
        $this->secureUrlRoutes[] = array('regex' => $regex, 'permission' => $permission);
    }

    public function handleRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has('exception')) {
            return;
        }

        $routePath = $request->getPathInfo();

        // Regex protected routes
        foreach ($this->secureUrlRoutes as $match) {
            if (preg_match($match['regex'], $routePath)) {
                if ($this->activeUser->hasPermission($match['permission']) == false) {
                    throw new PermissionsError('You don\'t have permission to access this page (regex secure).');
                }
            }
        }

        // Explicitly protected route
        if ($request->attributes->has('_permissions')) {
            $permissions = (array) $request->attributes->get('_permissions');

            foreach ($permissions as $permission) {
                if ($this->activeUser->hasPermission($permission) == false) {
                    throw new PermissionsError('You don\'t have permission to access this page (explicitly secure route).');
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
}