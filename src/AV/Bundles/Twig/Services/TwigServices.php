<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:02
 */

namespace AV\Bundles\Twig\Services;

use AV\Bundles\Twig\Services\Compiler\TwigEnvironmentPass;
use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TwigServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('twig', 'Twig_Environment')
            ->setArguments(array(
                new Reference('twig.filesystem'),
                array('cache' => '%cache_dir%/twig', 'debug' => '%dev_mode%')
            ))
        ;

        $container->register('twig.filesystem', 'AV\Bundles\Twig\TwigLoader\BundleTwigLoaderFilesystem')
            ->setArguments(array(new Reference('bundle.resource_locator')))
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