<?php

// example.com/src/app.php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$routes = new RouteCollection();

$routes->add('game', new Route('/game/{id}', array(
    'id' => null,
    '_controller' => 'Games\\Controller\\GamesController::newModelAction',
)));

$routes->add('game_form', new Route('/game_form/{id}', array(
    'id' => null,
    '_controller' => 'Games\\Controller\\GamesController::formAction',
)));

$routes->add('add_game', new Route('/add_game', array(
    '_controller' => 'Games\\Controller\\GamesController::addGameAction',
)));

$routes->add('join', new Route('/join/{id}', array(
    'id' => 1,
    '_controller' => 'Games\\Controller\\GamesController::joinAction',
)));

$routes->add('blank', new Route('/blank/{id}', array(
    'id' => 1,
    '_controller' => 'Games\\Controller\\GamesController::blankAction',
)));

$routes->add('subreq', new Route('/subreq', array(
    '_controller' => 'Games\\Controller\\GamesController::subRequestAction',
)));

$routes->add('stress', new Route('/stress', array(
    '_controller' => 'Games\\Controller\\GamesController::stressTestAction',
)));

$routes->add('setuser', new Route('/setuser/{id}', array(
    '_controller' => 'Games\\Controller\\GamesController::setUserAction',
)));

return $routes;