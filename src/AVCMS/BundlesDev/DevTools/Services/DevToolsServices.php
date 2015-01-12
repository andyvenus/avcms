<?php
/**
 * User: Andy
 * Date: 12/10/2014
 * Time: 14:18
 */

namespace AVCMS\BundlesDev\DevTools\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class DevToolsServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('dev_tool.bundle_translation_collector', 'AVCMS\BundlesDev\DevTools\Listener\BundleTranslationsCollector')
            ->setArguments([new Reference('translator'), new Reference('bundle_manager'), '%root_dir%', true])
            ->addTag('event.listener', ['event' => KernelEvents::TERMINATE, 'method' => 'onTerminate'])
        ;

        // Deny non-admin access to everything apart from /login in dev mode
        $container->register('auth.access_listener_dev', 'AVCMS\Core\Security\SiteOfflineAccessListener')
            ->setArguments([new Reference('security.token_storage'), new Reference('auth.access_decision_manager'), new Reference('auth.access_map_dev'), new Reference('auth.manager')])
            //->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handle', 'priority' => -101])
        ;

        $container->register('auth.access_map_dev', 'Symfony\Component\Security\Http\AccessMap')
            ->addMethodCall('add', [new Reference('auth.dev_request_matcher'), ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']])
        ;

        $container->register('auth.dev_request_matcher', 'Symfony\Component\HttpFoundation\RequestMatcher')
            ->setArguments(['^/(?!login)'])
        ;
    }
}
