<?php

use Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use AVCMS\Games\Event\ExampleFormEvent;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Routing\RequestContext;

$sc = new ContainerBuilder();

$loader = new YamlFileLoader($sc, new FileLocator(__DIR__ . '/../config'));
$loader->load('test.yml');

$sc->setParameter('container', $sc);

$sc->setParameter('dev_mode', true);

// Kernel & Listeners

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
    ->setArguments(array('AVCMS\\ErrorController::exceptionAction'))
;
$sc->register('listener.security.routes', 'AVCMS\Core\Security\SecureRoutes')
    ->setArguments(array(new Reference('active.user'), '%routes%'))
    ->addMethodCall('addRouteMatcherPermission', array('/^\/admin/', 'admin'))
;

$sc->register('active.user', 'AVCMS\Bundles\UsersBase\ActiveUser')
    ->setArguments(array(
        new Reference('model.factory'),
        'AVBlog\Bundles\Users\Model\Users',
        'AVCMS\Bundles\UsersBase\Model\Sessions',
        'AVCMS\Bundles\UsersBase\Model\Groups',
        'AVCMS\Bundles\UsersBase\Model\GroupPermissions'
    ));

$sc->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')))
    ->addMethodCall('addSubscriber', array(new Reference('active.user')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.security.routes')))
    ->addMethodCall('addSubscriber', array(new ExampleFormEvent()))
;
$sc->register('framework', 'AVCMS\Core\Framework')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')))
;

// Other HttpKernel components

$sc->register('fragment.handler', 'Symfony\Component\HttpKernel\Fragment\FragmentHandler')
    ->setArguments(array(array(new Reference('inline.fragment.renderer'))))
    ->addMethodCall('setRequest', array($request)) // todo: move this?
;

$sc->register('inline.fragment.renderer', 'Symfony\Component\HttpKernel\Fragment\InlineFragmentRenderer')
    ->setArguments(array(new Reference('framework')));

$request_context = new RequestContext();
$request_context->fromRequest($request);

$sc->register('routing.url.generator', 'Symfony\Component\Routing\Generator\UrlGenerator')
    ->setArguments(array('%routes%', $request_context));


// Assets


// Translation

$sc->register('translator', 'AVCMS\Core\Translation\Translator')
    ->setArguments(array('en_GB'), new \Symfony\Component\Translation\MessageSelector())
    ->addMethodCall('addLoader', array('array', new \Symfony\Component\Translation\Loader\ArrayLoader()))
    ->addMethodCall('addResource', array('array', array(), /*
        array(
            'That name is already in use' => 'Arr, that name be already in use',
            'Name' => 'FRUNCH NAME',
            'Cat One' => 'Le Category Une',
            'Published' => 'Pubèlishé',
            'Submit' => 'Procesèur',
            'Cannot find an account that has that username or email address' => 'Oh vue du nuet finel the user',
            'Title' => 'Oh qui le Titlè',
            'Blog Posts' => 'Meepz'
        ),*/
        'en_GB'))
;

// Templates



// Twig & Extensions

$sc->register('twig.filesystem', 'AVCMS\Core\View\TwigLoaderFilesystem')
    ->setArguments(array('templates', array('original.twig' => 'replacement.twig')))
    ->addMethodCall('addPath', array('templates/admin/avcms', 'admin'))
;

$sc->register('twig', 'Twig_Environment')
    ->setArguments(array(
        new Reference('twig.filesystem'),
        array('cache' => 'cache', 'debug' => true)
    ))
    ->addMethodCall('addExtension', array(new \AVCMS\Core\View\TwigModuleExtension()))
    ->addMethodCall('addExtension', array(new \AVCMS\Core\Form\Twig\FormExtension()))
    ->addMethodCall('addExtension', array(new MarkdownExtension(new MichelfMarkdownEngine())))
    ->addMethodCall('addExtension', array(new Reference('twig.routing.extension')))
    ->addMethodCall('addExtension', array(new Reference('twig.asset_manager.extension')))
    ->addMethodCall('addExtension', array(new Reference('twig.http-kernel.extension')))
    ->addMethodCall('addExtension', array(new Reference('twig.translation.extension')))
;

$sc->register('twig.http-kernel.extension', 'Symfony\Bridge\Twig\Extension\HttpKernelExtension')
    ->setArguments(array(new Reference('fragment.handler')))
;

$sc->register('twig.routing.extension', 'Symfony\Bridge\Twig\Extension\RoutingExtension')
    ->setArguments(array(new Reference('routing.url.generator')));

$sc->register('twig.translation.extension', 'Symfony\Bridge\Twig\Extension\TranslationExtension')
    ->setArguments(array(new Reference('translator')));

$sc->register('twig.asset_manager.extension', 'AVCMS\Core\AssetManager\Twig\AssetManagerExtension')
    ->setArguments(array(new Reference('asset_manager'), '%dev_mode%'));

$sc->register('asset_manager', 'AVCMS\Core\AssetManager\AssetManager')
    ->setArguments(array(new Reference('bundle_manager')))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'jquery.js'), \AVCMS\Core\AssetManager\AssetManager::SHARED, 90))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'bootstrap.min.js')))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'bootstrap-markdown.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'markdown.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'to-markdown.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'select2.min.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'jquery.history.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'jquery.nanoscroller.min.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'admin/admin_browser.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'admin/admin_navigation.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'avcms_form.js'), 'admin'))
;

$sc->register('model.factory', 'AVCMS\Core\Model\ModelFactory')
    ->setArguments(array(new Reference('query_builder'), new Reference('dispatcher')))
    ->addMethodCall('addModelAlias', array('users', 'AVBlog\Bundles\Users\Model\Users'))
;

// Database

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
    ->setArguments(array('mysql', $dbconfig, 'QB', null, new Reference('dispatcher')));

// Bundles

$bundles = array(
    'Blog' => array('namespace' => 'AVCMS\Bundles\Blog'),
    'Users' => array('namespace' => 'AVBlog\Bundles\Users'),
    'Assets' => array('namespace' => 'AVCMS\Bundles\Assets')
);

$sc->register('bundle_manager', 'AVCMS\Core\BundleManager\BundleManager')
    ->setArguments(array($bundles, '%container%'));

return $sc;