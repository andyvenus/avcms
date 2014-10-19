<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:02
 */

namespace AV\Bundles\Twig\Services;

use AVCMS\Bundles\CmsFoundation\Services\Compiler\TwigEnvironmentPass;
use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TwigServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('twig', 'Twig_Environment')
            ->setArguments(array(
                new Reference('twig.filesystem'),
                array('cache' => 'cache/twig', 'debug' => '%dev_mode%')
            ))
        ;

        $container->register('twig.filesystem', 'AVCMS\Core\View\TwigLoaderFilesystem')
            ->setArguments(array(new Reference('bundle.resource_locator'), new Reference('settings_manager')))
            ->addMethodCall('addPath', array('templates/admin/avcms', 'admin'))
            ->addMethodCall('addPath', array('templates/dev/avcms', 'dev'))
        ;

        $container->register('twig.http.kernel_extension', 'Symfony\Bridge\Twig\Extension\HttpKernelExtension')
            ->setArguments(array(new Reference('fragment_handler')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.routing_extension', 'Symfony\Bridge\Twig\Extension\RoutingExtension')
            ->setArguments(array(new Reference('router')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.code_extension', 'Symfony\Bridge\Twig\Extension\CodeExtension')
            ->setArguments(array(null, '/', 'UTF-8'))
            ->addTag('twig.extension')
        ;

        $container->register('twig.site_url_extension', 'AVCMS\Core\View\Extension\SiteUrlTwigExtension')
            ->setArguments(array(new Reference('request.stack')))
            ->addTag('twig.extension')
        ;

        $container->addCompilerPass(new TwigEnvironmentPass());
    }
} 