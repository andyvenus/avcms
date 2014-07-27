<?php

use Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use AVCMS\Games\Event\ExampleFormEvent;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Routing\RequestContext;

$sc = new ContainerBuilder();

$sc->setParameter('container', $sc);

$sc->setParameter('dev_mode', true);

// Kernel & Listeners

$sc->register('context', 'Symfony\Component\Routing\RequestContext');

$sc->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
   ->setArguments(array('%routes%', new Reference('context')))
;
$sc->register('resolver', 'AVCMS\Core\Controller\ControllerResolver')
   ->setArguments(array('%container%', new Reference('bundle_manager')));

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

$sc->register('active.user', 'AVCMS\Bundles\Users\ActiveUser')
    ->setArguments(array(
        new Reference('model.factory'),
        'AVCMS\Bundles\Users\Model\Users',
        'AVCMS\Bundles\Users\Model\Sessions',
        'AVCMS\Bundles\Users\Model\Groups',
        'AVCMS\Bundles\Users\Model\GroupPermissions'
    ))
;

$sc->register('entity.date.maker', 'AVCMS\Core\Controller\Events\DateMaker');

$sc->register('entity.author.assigner', 'AVCMS\Core\Controller\Events\AuthorAssigner')
    ->setArguments(array(new Reference('active.user')))
;

$sc->register('dispatcher', 'Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher')
    ->setArguments([$sc])
    ->addMethodCall('addSubscriber', array(new Reference('listener.router')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.response')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.exception')))
    ->addMethodCall('addSubscriber', array(new Reference('active.user')))
    ->addMethodCall('addSubscriber', array(new Reference('listener.security.routes')))
    ->addMethodCall('addSubscriber', array(new Reference('entity.date.maker')))
    ->addMethodCall('addSubscriber', array(new Reference('entity.author.assigner')))
    ->addMethodCall('addSubscriber', array(new Reference('update.tags')))
    ->addMethodCall('addSubscriber', array(new Reference('validator.model.injector')))
    ->addMethodCall('addSubscriber', array(new Reference('controller.inject.bundle')))
    ->addMethodCall('addSubscriber', array(new Reference('csrf.form.plugin')))
;

$sc->register('framework', 'AVCMS\Core\Framework')
    ->setArguments(array(new Reference('dispatcher'), new Reference('resolver')))
;

$sc->register('csrf.token', 'AVCMS\Core\Security\Csrf\CsrfToken');

$sc->register('csrf.form.plugin', 'AVCMS\Core\Security\Csrf\Events\CsrfFormPlugin')
    ->setArguments(array(new Reference('csrf.token')))
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
    ->setArguments(array('%routes%', $request_context))
    ->addMethodCall('setStrictRequirements', array(false))
;

$sc->register('controller.inject.bundle', 'AVCMS\Core\Bundle\Events\ControllerInjectBundle')
    ->setArguments(array(new Reference('bundle_manager')));

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

// Some Validation things

$sc->register('validator.model.injector', 'AVCMS\Core\Validation\Events\RuleModelFactoryInjector')
    ->setArguments(array(new Reference('model.factory')))
;

// Templates



// Twig & Extensions

$sc->register('twig.filesystem', 'AVCMS\Core\View\TwigLoaderFilesystem')
    ->setArguments(array('templates', array('original.twig' => 'replacement.twig')))
    ->addMethodCall('addPath', array('templates/admin/avcms', 'admin'))
    ->addMethodCall('addPath', array('templates/dev/avcms', 'dev'))
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
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'admin/admin_navigation.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'admin/admin_browser.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'admin/admin_misc.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'avcms_form.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('css', 'bootstrap-datetimepicker.css'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'bootstrap-datetimepicker.min.js'), 'admin'))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'moment.min.js'), 'admin', 70))
    ->addMethodCall('add', array(new \AVCMS\Core\AssetManager\Asset\AppFileAsset('javascript', 'avcms_config.js'), \AVCMS\Core\AssetManager\AssetManager::SHARED, 80))
;

$sc->register('model.factory', 'AVCMS\Core\Model\ModelFactory')
    ->setArguments(array(new Reference('query.builder'), new Reference('dispatcher'), new Reference('taxonomy.manager')))
    ->addMethodCall('addModelAlias', array('users', 'AVCMS\Bundles\Users\Model\Users'))
;

$sc->register('taxonomy.manager', 'AVCMS\Core\Taxonomy\ContainerAwareTaxonomyManager')
    ->setArguments(array($sc))
    ->addMethodCall('addContainerTaxonomy', array('tags', 'tags.taxonomy'))
;

$sc->register('tags.taxonomy', 'AVCMS\Bundles\Tags\Taxonomy\TagsTaxonomy')
    ->setArguments(array(new Reference('tags.model'), new Reference('tags.taxonomy.model')))
;

$sc->register('tags.model', 'AVCMS\Bundles\Tags\Model\TagsModel')
    ->setArguments(array('AVCMS\Bundles\Tags\Model\TagsModel'))
    ->setFactoryService('model.factory')
    ->setFactoryMethod('create')
;

$sc->register('tags.taxonomy.model', 'AVCMS\Bundles\Tags\Model\TagsTaxonomyModel')
    ->setArguments(array('AVCMS\Bundles\Tags\Model\TagsTaxonomyModel'))
    ->setFactoryService('model.factory')
    ->setFactoryMethod('create')
;

$sc->register('form.transformer.manager', 'AVCMS\Core\Form\Transformer\TransformerManager')
    ->addMethodCall('registerTransformer', array(new \AVCMS\Core\Form\Transformer\UnixTimestampTransformer()))
;

$sc->register('slug.generator', 'AVCMS\Core\SlugGenerator\SlugGenerator');

// Database

$sc->setParameter('query.builder.config', array(
    'driver'    => 'mysql', // Db driver
    'host'      => 'localhost',
    'database'  => 'avcms',
    'username'  => 'root',
    'password'  => 'root',
    'charset'   => 'utf8', // Optional
    'collation' => 'utf8_unicode_ci', // Optional
    'prefix'    => 'avms_', // Table prefix, optional
));

$sc->register('query.builder.factory', 'AVCMS\Core\Database\Connection')
    ->setArguments(array('mysql', '%query.builder.config%', 'QB', null, new Reference('dispatcher')));

$sc->setDefinition('query.builder', new Definition('AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler'))
    ->setFactoryService('query.builder.factory')
    ->setFactoryMethod('getQueryBuilder');

// Bundles

$bundles = array('Users', 'Admin', 'Assets', 'Blog', 'BundleManager');

$sc->register('bundle_manager', 'AVCMS\Core\Bundle\BundleManager')
    ->setArguments(array($bundles, array('AVCMS\Bundles', 'AVBlog\Bundles'), '%container%'));

// Tags
$sc->register('update.tags', 'AVCMS\Bundles\Tags\Events\UpdateTags')
    ->setArguments(array(new Reference('taxonomy.manager')))
;

return $sc;