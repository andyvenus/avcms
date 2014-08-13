<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add('home', new Route('/', array(
    '_controller' => 'AVCMS\Bundles\Blog\Controller\BlogController::blogArchiveAction',
    '_bundle' => 'Blog'
)));

return $routes;