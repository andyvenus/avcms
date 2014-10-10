<?php
/**
 * User: Andy
 * Date: 06/08/2014
 * Time: 19:47
 */

namespace AVCMS\Bundles\Framework\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class FrameworkServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        // Kernel & Core Kernel Listeners
        $container->register('http_kernel', 'Symfony\Component\HttpKernel\HttpKernel')
            ->setArguments(array(new Reference('dispatcher'), new Reference('resolver'), new Reference('request.stack')))
        ;

        $container->register('context', 'Symfony\Component\Routing\RequestContext');

        $container->register('router', 'AVCMS\Core\Kernel\Router')
            ->setArguments(array(new Reference('router.loader.yaml'), 'routes.yml', array('cache_dir' => 'cache', 'debug' => '%dev_mode%'), new Reference('bundle_manager')))
        ;

        $container->register('app_config.file_locator', 'Symfony\Component\Config\FileLocator')
            ->setArguments(array('app/config'))
        ;

        $container->register('router.loader.yaml', 'Symfony\Component\Routing\Loader\YamlFileLoader')
            ->setArguments(array(new Reference('app_config.file_locator')))
        ;

        $container->register('resolver', 'AVCMS\Core\Controller\ControllerResolver')
            ->setArguments(array(new Reference('service_container'), new Reference('bundle_manager')));

        $container->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
            ->setArguments(array(new Reference('router'), null, null, new Reference('request.stack')))
            ->addTag('event.subscriber')
        ;
        $container->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
            ->setArguments(array('UTF-8'))
            ->addTag('event.subscriber')
        ;
        $container->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener')
            ->setArguments(array('AVCMS\\Core\\Controller\\ErrorController::exceptionAction'))
            ->addTag('event.subscriber')
        ;

        /*
        $container->register('listener.security.routes', 'AVCMS\Core\Security\SecureRoutes')
            ->setArguments(array(new Reference('active.user')))
            ->addMethodCall('addRouteMatcherPermission', array('/^\/admin/', 'admin'))
            ->addTag('event.subscriber')
        ;
        */

        $container->register('request_matcher', 'Symfony\Component\HttpFoundation\RequestMatcher');

        $container->register('http.utils', 'Symfony\Component\Security\Http\HttpUtils')
            ->setArguments(array(new Reference('router'), new Reference('router')))
        ;

        // Request

        $container->register('fragment_handler', 'Symfony\Component\HttpKernel\Fragment\FragmentHandler')
            ->setArguments(array(array(new Reference('inline_fragment_renderer')), '%dev_mode%', new Reference('request.stack')))
        ;

        $container->register('request.stack', 'Symfony\Component\HttpFoundation\RequestStack');

        $container->register('inline_fragment_renderer', 'Symfony\Component\HttpKernel\Fragment\InlineFragmentRenderer')
            ->setArguments(array(new Reference('http_kernel')));

        // Event Dispatcher

        $container->addCompilerPass(new RegisterListenersPass('dispatcher', 'event.listener', 'event.subscriber'));

        $container->register('dispatcher', 'Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher')
            ->setArguments(array(new Reference('service_container')))
        ;

        // Sessions

        $container->register('session', 'Symfony\Component\HttpFoundation\Session\Session');

        $container->register('listener.session', 'AVCMS\Core\Security\Subscriber\SessionSubscriber')
            ->setArguments([new Reference('service_container')])
            ->addTag('event.subscriber')
        ;
    }
}