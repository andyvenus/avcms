<?php
/**
 * User: Andy
 * Date: 06/08/2014
 * Time: 19:47
 */

namespace AV\Bundles\Framework\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpKernel\KernelEvents;

class FrameworkServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        // Kernel & Core Kernel Listeners
        $container->register('http_kernel', 'Symfony\Component\HttpKernel\HttpKernel')
            ->setArguments(array(new Reference('dispatcher'), new Reference('resolver'), new Reference('request.stack')))
        ;

        $container->register('context', 'Symfony\Component\Routing\RequestContext');

        $container->register('router', 'AV\Kernel\Router')
            ->setArguments(array(new Reference('router.loader.yaml'), 'routes.yml', '%app_dir%', array('cache_dir' => '%cache_dir%', 'debug' => '%dev_mode%', 'strict_requirements' => false), new Reference('bundle_manager')))
        ;

        $container->register('app_config.file_locator', 'Symfony\Component\Config\FileLocator')
            ->setArguments(array('%config_dir%'))
        ;

        $container->register('router.loader.yaml', 'Symfony\Component\Routing\Loader\YamlFileLoader')
            ->setArguments(array(new Reference('app_config.file_locator')))
        ;

        $container->register('resolver', 'AV\Controller\ControllerResolver')
            ->setArguments(array(new Reference('service_container'), new Reference('bundle_manager')));

        $container->register('listener.router', 'AVCMS\Core\Kernel\RouterListener\RouterListener')
            ->setArguments(array(new Reference('router'), new Reference('request.stack'), null, null))
            ->addTag('event.subscriber')
        ;
        $container->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
            ->setArguments(array('UTF-8'))
            ->addTag('event.subscriber')
        ;

        $container->register('request_matcher', 'Symfony\Component\HttpFoundation\RequestMatcher');

        $container->register('http.utils', 'Symfony\Component\Security\Http\HttpUtils')
            ->setArguments(array(new Reference('router'), new Reference('router')))
        ;

        // Request

        $container->register('fragment_handler', 'Symfony\Component\HttpKernel\Fragment\FragmentHandler')
            ->setArguments(array(new Reference('request.stack'), array(new Reference('inline_fragment_renderer')), '%dev_mode%'))
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

        $container->register('session', 'Symfony\Component\HttpFoundation\Session\Session')
            ->setArguments([null, new Reference('session.namespaced_attribute_bag')])
        ;

        $container->register('session.namespaced_attribute_bag', 'Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag');

        $container->register('listener.session', 'AVCMS\Core\Security\Subscriber\SessionSubscriber')
            ->setArguments([new Reference('service_container')])
            ->addTag('event.subscriber')
        ;

        // CSRF protection
        $container->register('csrf.token', 'AV\Csrf\CsrfToken')
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handleRequest'])
            ->addTag('event.listener', ['event' => KernelEvents::RESPONSE, 'method' => 'handleResponse'])
        ;
    }
}
