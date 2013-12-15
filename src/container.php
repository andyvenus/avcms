<?php

use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;

$sc = new DependencyInjection\ContainerBuilder();

$sc->setParameter('container', $sc);

$sc->register('context', 'Symfony\Component\Routing\RequestContext');

$sc->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
   ->setArguments(array('%routes%', new Reference('context')))
;
$sc->register('resolver', 'AVCMS\Controller\ControllerResolver')
   ->setArguments(array('%container%'));

$sc->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
    ->setArguments(array(new Reference('matcher')))
;
$sc->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
    ->setArguments(array('UTF-8'))
;
$sc->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener')
    ->setArguments(array('Games\\Controller\\ErrorController::exceptionAction'))
;

$sc->register('active.user', 'AVCMS\User\ActiveUser')
    ->setArguments(array('%container%', new Reference('model.factory')));

$sc->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')))
    ->addMethodCall('addSubscriber', array(new Reference('active.user')))
;
$sc->register('framework', 'AVCMS\Framework')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')))
;

$sc->register('twig', 'Twig_Environment')
    ->setArguments(array(
        new Twig_Loader_String(),
        array('cache' => 'cache')
    ));
;

$sc->register('model.factory', 'AVCMS\Model\ModelFactory')
    ->setArguments(array('%container%'))
;

$dbconfig = array(
    'driver'    => 'mysql', // Db driver
    'host'      => 'localhost',
    'database'  => 'avms',
    'username'  => 'root',
    'password'  => 'root',
    'charset'   => 'utf8', // Optional
    'collation' => 'utf8_unicode_ci', // Optional
    'prefix'    => 'avms_', // Table prefix, optional
);

$sc->register('query_builder', 'AVCMS\Database\Connection')
    ->setArguments(array('mysql', $dbconfig, 'QB'));

return $sc;