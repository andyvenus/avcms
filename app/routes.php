<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add('home', new Route('/', array(
    '_controller' => 'AVCMS\Bundles\Blog\Controller\BlogController::blogHomeAction',
    '_bundle' => 'Blog'
)));

$routes->add('generate', new Route('/generate/{table}', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GenerateController::generateAction',
)));

return $routes;