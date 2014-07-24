<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add('home', new Route('/', array(
    '_controller' => 'AVCMS\Bundles\Blog\Controller\BlogController::blogHomeAction',
    '_bundle' => 'Blog'
)));

$routes->add('game', new Route('/game/{id}', array(
    'id' => null,
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::newModelAction',
)));

$routes->add('z', new Route('/z', array(
    'id' => null,
    '_controller' => 'AVCMS\\Test\\MaController::action',
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
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::testSubRequest',
)));

$routes->add('stress', new Route('/stress', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::stressTestAction',
    '_permissions' => array('ADMIN_BLOG', 'EDIT')
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

$routes->add('form_test', new Route('/forms/{id}', array(
    'id' => 1,
    '_controller' => 'AVCMS\\Games\\Controller\\FormController::formTestAction',
)));

$routes->add('form_array_test', new Route('/forms_array/{id}', array(
    'id' => 1,
    '_controller' => 'AVCMS\\Games\\Controller\\FormController::formArrayTestAction',
)));

$routes->add('validator_test', new Route('/validation', array(
    '_controller' => 'AVCMS\\Games\\Controller\\FormController::validatorTestAction',
)));

$routes->add('upload_form', new Route('/upload_form', array(
    '_controller' => 'AVCMS\\Games\\Controller\\FormController::fileUploadAction',
)));

$routes->add('assetic', new Route('/assetic', array(
    '_controller' => 'AVCMS\\Games\\Controller\\GamesController::asseticAction',
)));


return $routes;