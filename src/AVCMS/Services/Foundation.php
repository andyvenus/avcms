<?php
/**
 * User: Andy
 * Date: 06/08/2014
 * Time: 19:47
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class Foundation implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        // Kernel & Listeners
        $container->register('http_kernel', 'Symfony\Component\HttpKernel\HttpKernel')
            ->setArguments(array(new Reference('dispatcher'), new Reference('resolver'), new Reference('request.stack')))
        ;

        $container->register('context', 'Symfony\Component\Routing\RequestContext');

        $container->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
            ->setArguments(array('%routes%', new Reference('context')))
        ;
        $container->register('resolver', 'AVCMS\Core\Controller\ControllerResolver')
            ->setArguments(array('%container%', new Reference('bundle_manager')));

        $container->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
            ->setArguments(array(new Reference('matcher')))
            ->addTag('event.subscriber')
        ;
        $container->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
            ->setArguments(array('UTF-8'))
            ->addTag('event.subscriber')
        ;
        $container->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener')
            ->setArguments(array('AVCMS\\ErrorController::exceptionAction'))
            ->addTag('event.subscriber')
        ;
        $container->register('listener.security.routes', 'AVCMS\Core\Security\SecureRoutes')
            ->setArguments(array(new Reference('active.user'), '%routes%'))
            ->addMethodCall('addRouteMatcherPermission', array('/^\/admin/', 'admin'))
            ->addTag('event.subscriber')
        ;

        // Request

        $container->register('fragment_handler', 'Symfony\Component\HttpKernel\Fragment\FragmentHandler')
            ->setArguments(array(array(new Reference('inline_fragment_renderer')), false, new Reference('request.stack')))
        ;

        $container->register('request.stack', 'Symfony\Component\HttpFoundation\RequestStack');

        $container->register('inline_fragment_renderer', 'Symfony\Component\HttpKernel\Fragment\InlineFragmentRenderer')
            ->setArguments(array(new Reference('http_kernel')));

        $container->register('routing.url_generator', 'Symfony\Component\Routing\Generator\UrlGenerator')
            ->setArguments(array('%routes%', new Reference('context')))
            ->addMethodCall('setStrictRequirements', array(false))
        ;
        
        // Event Dispatcher

        $container->addCompilerPass(new RegisterListenersPass('dispatcher', 'event.listener', 'event.subscriber'));

        $container->register('dispatcher', 'Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher')
            ->setArguments(array('%container%'))
        ;
    }
}