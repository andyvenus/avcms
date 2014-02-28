<?php

use Assetic\Asset\AssetCollection;
use Assetic\Asset\GlobAsset;
use AVCMS\Games\Event\ExampleFormEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Routing\RequestContext;

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

$sc->register('active.user', 'AVCMS\Users\ActiveUser')
    ->setArguments(array(new Reference('model.factory'), 'AVBlog\Users\Model\Users', 'AVCMS\Users\Model\Sessions'));

$sc->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')))
    ->addMethodCall('addSubscriber', array(new Reference('active.user')))
    ->addMethodCall('addSubscriber', array(new ExampleFormEvent()))
;
$sc->register('framework', 'AVCMS\Core\Framework')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')))
;


$a = new GlobAsset('web/js/*.js');
$a->setTargetPath('shared_js.js');
$b = new GlobAsset('web/js/frontend/*.js');
$b->setTargetPath('frontend_js.js');
$c = new GlobAsset('web/js/admin/*.js');
$c->setTargetPath('admin_js.js');


$sc->register('assetic.manager', 'Assetic\AssetManager')
    ->addMethodCall('set', array('shared_js', $a))
    ->addMethodCall('set', array('frontend_js', $b))
    ->addMethodCall('set', array('admin_js', $c))
;

$sc->register('assetic.factory', 'Assetic\Factory\AssetFactory')
    ->setArguments(array('/web'))
    ->addMethodCall('setAssetManager', array(new Reference('assetic.manager')))
    ->addMethodCall('setDebug', array(true))
;

$sc->register('twig.filesystem', 'AVCMS\Core\View\TwigLoaderFilesystem')
    ->setArguments(array('templates', array('original.twig' => 'replacement.twig')))
    ->addMethodCall('addPath', array('src/AVCMS/Games/View/Templates', 'games'))
;

$sc->register('twig', 'Twig_Environment')
    ->setArguments(array(
        new Reference('twig.filesystem'),
        array('cache' => 'cache', 'debug' => true)
    ))
    ->addMethodCall('addExtension', array(new \AVCMS\Core\View\TwigModuleExtension()))
    ->addMethodCall('addExtension', array(new \AVCMS\Core\Form\Twig\FormExtension()))
    ->addMethodCall('addExtension', array(new Reference('twig.routing.extension')))
    ->addMethodCall('addExtension', array(new Reference('twig.assetic.extension')))
;

$sc->register('twig.routing.extension', 'Symfony\Bridge\Twig\Extension\RoutingExtension')
    ->setArguments(array(new Reference('routing.url.generator')));

$sc->register('twig.assetic.extension', 'Assetic\Extension\Twig\AsseticExtension')
    ->setArguments(array(new Reference('assetic.factory')));

$request_context = new RequestContext();
$request_context->fromRequest($request);

$sc->register('routing.url.generator', 'Symfony\Component\Routing\Generator\UrlGenerator')
    ->setArguments(array('%routes%', $request_context));

$sc->register('model.factory', 'AVCMS\Core\Model\ModelFactory')
    ->setArguments(array(new Reference('query_builder'), new Reference('dispatcher')))
;

$dbconfig = array(
    'driver'    => 'mysql', // Db driver
    'host'      => 'localhost',
    'database'  => 'avcms',
    'username'  => 'root',
    'password'  => 'root',
    'charset'   => 'utf8', // Optional
    'collation' => 'utf8_unicode_ci', // Optional
    'prefix'    => 'avms_', // Table prefix, optional
);

$sc->register('query_builder', 'AVCMS\Core\Database\Connection') // TODO: change so ->getQueryBuilder doesn't have to be used
    ->setArguments(array('mysql', $dbconfig, 'QB'));

$bundles = array(
    'Blog' => 'AVCMS\Blog',
    'Users' => 'AVBlog\Users'
);

$sc->register('bundle.initializer', 'AVCMS\Core\BundleManager\BundleInitializer')
    ->setArguments(array($bundles, '%container%'));

return $sc;