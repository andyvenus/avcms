<?php
/**
 * User: Andy
 * Date: 05/12/14
 * Time: 14:59
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class MiscServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('router', 'AVCMS\Core\Kernel\Router')
            ->setArguments(array(new Reference('router.loader.yaml'), 'routes.yml', '%app_dir%', '%root_dir%', array('cache_dir' => '%cache_dir%', 'debug' => '%dev_mode%', 'strict_requirements' => false), new Reference('bundle_manager')))
        ;

        $container->register('site_offline_handler', 'AVCMS\Core\Security\SiteOfflineHandler')
            ->setArguments([new Reference('auth.access_denied_handler')])
            ->addTag('event.listener', ['event' => KernelEvents::EXCEPTION, 'method' => 'onKernelException', 'priority' => 100])
        ;

        $container->register('site_url', 'AVCMS\Core\Kernel\SiteRootUrl')
            ->setArguments([new Reference('request.stack')])
        ;

        $container->register('auth.site_offline_listener', 'AVCMS\Core\Security\SiteOfflineAccessListener')
            ->setArguments([new Reference('security.token_storage'), new Reference('auth.access_decision_manager'), new Reference('auth.access_map_site_offline'), new Reference('auth.manager'), new Reference('settings_manager')])
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handle', 'priority' => -101])
        ;

        $container->register('auth.access_map_site_offline', 'Symfony\Component\Security\Http\AccessMap')
            ->addMethodCall('add', [new Reference('auth.site_offline_request_matcher'), ['ADMIN_ACCESS']])
        ;

        $container->register('auth.site_offline_request_matcher', 'Symfony\Component\HttpFoundation\RequestMatcher')
            ->setArguments(['^/(?!login)(?!facebook-register)'])
        ;

        $container->register('subscriber.post_install', 'AVCMS\Bundles\CmsFoundation\Subscribers\PostInstallSubscriber')
            ->setArguments([new Reference('bundle_manager'), new Reference('asset_manager'), new Reference('public_file_mover')])
            ->addTag('event.subscriber')
        ;
    }
}
