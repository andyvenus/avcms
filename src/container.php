<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$sc = new ContainerBuilder();

$sc->setParameter('container', $sc);

$sc->register('context', 'Symfony\Component\Routing\RequestContext');

$sc->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
   ->setArguments(array('%routes%', new Reference('context')))
;
$sc->register('resolver', 'AVCMS\Core\Controller\ControllerResolver')
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

$sc->register('active.user', 'AVCMS\Core\User\ActiveUser')
    ->setArguments(array('%container%', new Reference('model.factory')));

$sc->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')))
    //->addMethodCall('addSubscriber', array(new Reference('active.user')))
;
$sc->register('framework', 'AVCMS\Core\Framework')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')))
;

$loader = new \AVCMS\Games\View\TwigLoaderFilesystem('templates', array('index.twig' => 'index2.twig'));
$loader->addPath('src/AVCMS/Games/View/Templates', 'games');

$sc->register('twig', 'Twig_Environment')
    ->setArguments(array(
        $loader,
        array('cache' => 'cache', 'debug' => true)
    ))
    ->addMethodCall('addExtension', array(new \AVCMS\Core\View\TwigModuleExtension()))
;

$sc->register('model.factory', 'AVCMS\Core\Model\ModelFactory')
    ->setArguments(array(new Reference('query_builder'), new Reference('dispatcher')))
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

$sc->register('query_builder', 'AVCMS\Core\Database\Connection') // TODO: change so ->getQueryBuilder doesn't have to be used
    ->setArguments(array('mysql', $dbconfig, 'QB'));

return $sc;