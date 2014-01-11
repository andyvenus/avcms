<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add('game', new Route('/game/{id}', array(
    'id' => null,
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::newModelAction',
)));

$routes->add('game_form', new Route('/game_form/{id}', array(
    'id' => null,
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::formAction',
)));

$routes->add('sym_form', new Route('/sym_form/{id}', array(
    'id' => null,
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::symFormAction',
)));

$routes->add('add_game', new Route('/add_game', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::addGameAction',
)));

$routes->add('join', new Route('/join/{id}', array(
        'id' => 1,
        '_controller' => 'AVCMS\\Games\\Controller\\GamesController::joinAction',
        '_name'=> 'Join',
        '_description' => "This is the join route",
        '_context' => 'front-end'
    )
));

$routes->add('blank', new Route('/blank/{id}', array(
    'id' => 1,
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::blankAction',
)));

$routes->add('subreq', new Route('/subreq', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::subRequestAction',
)));

$routes->add('stress', new Route('/stress', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::stressTestAction',
)));

$routes->add('setuser', new Route('/setuser/{id}', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::setUserAction',
)));

$routes->add('translate', new Route('/translate', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::translateAction',
)));

$routes->add('generate', new Route('/generate/{table}', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GenerateController::generateAction',
)));

return $routes;