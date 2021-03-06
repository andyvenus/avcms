<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 15:13
 */

namespace AVCMS\Bundles\Admin\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class AdminServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('slug.generator', 'Cocur\Slugify\Slugify')
            ->addMethodCall('addRule', ["'", ''])
        ;

        $container->register('listener.entity.date', 'AVCMS\Bundles\Admin\Listeners\DateMaker')
            ->addTag('event.subscriber')
        ;

        $container->register('listener.entity.author', 'AVCMS\Bundles\Admin\Listeners\AuthorAssigner')
            ->setArguments(array(new Reference('security.token_storage')))
            ->addTag('event.subscriber')
        ;

        // ACCESS LISTENER

        $container->register('admin.request_matcher', 'Symfony\Component\HttpFoundation\RequestMatcher')
            ->setArguments(['^/admin'])
        ;

        $container->register('admin.access_listener', 'Symfony\Component\Security\Http\Firewall\AccessListener')
            ->setArguments([new Reference('security.token_storage'), new Reference('auth.access_decision_manager'), new Reference('admin.access_map'), new Reference('auth.manager')])
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handle', 'priority' => -101])
        ;

        $container->register('admin.access_map', 'Symfony\Component\Security\Http\AccessMap')
            ->addMethodCall('add', [new Reference('admin.request_matcher'), ['ADMIN_ACCESS']])
        ;

        $container->register('admin.access_listener.fully', 'Symfony\Component\Security\Http\Firewall\AccessListener')
            ->setArguments([new Reference('security.token_storage'), new Reference('auth.access_decision_manager'), new Reference('admin.access_map.fully'), new Reference('auth.manager')])
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handle', 'priority' => -101])
        ;

        $container->register('admin.access_map.fully', 'Symfony\Component\Security\Http\AccessMap')
            ->addMethodCall('add', [new Reference('admin.request_matcher'), ['IS_AUTHENTICATED_FULLY']])
        ;

        // AJAX DEPTH TWIG EXTENSION
        $container->register('ajax_depth.twig_extension', 'AVCMS\Bundles\Admin\TwigExtension\AjaxDepthTwigExtension')
            ->setArguments([new Reference('request.stack')])
            ->addTag('twig.extension')
        ;
    }
}
