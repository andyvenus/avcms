<?php

// example.com/src/app.php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$routes = new RouteCollection();

$routes->add('game', new Route('/game/{id}', array(
    'id' => null,
    '_controller' => 'Calendar\\Controller\\GamesController::newModelAction',
)));

$routes->add('game_form', new Route('/game_form/{id}', array(
    'id' => null,
    '_controller' => 'Calendar\\Controller\\GamesController::formAction',
)));

$routes->add('add_game', new Route('/add_game', array(
    '_controller' => 'Calendar\\Controller\\GamesController::addGameAction',
)));

$routes->add('join', new Route('/join/{id}', array(
    'id' => 1,
    '_controller' => 'Calendar\\Controller\\GamesController::joinAction',
)));

$routes->add('blank', new Route('/blank/{id}', array(
    'id' => 1,
    '_controller' => 'Calendar\\Controller\\GamesController::blankAction',
)));

return $routes;