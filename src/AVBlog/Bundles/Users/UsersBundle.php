<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 15:36
 */

namespace AVBlog\Bundles\Users;

use AVCMS\Core\Bundle\Bundle;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class UsersBundle extends Bundle
{
    public function routes(RouteCollection $routes)
    {
        $routes->add('login', new Route('/login', array(
            '_controller' => 'AVCMS\\Bundles\\UsersBase\\Controller\\UsersController::loginAction',
        )));
    }
} 