<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:02
 */

namespace AVCMS\Services;

use Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class Twig implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('twig', 'Twig_Environment')
            ->setArguments(array(
                new Reference('twig.filesystem'),
                array('cache' => 'cache/twig', 'debug' => '%dev_mode%')
            ))
            ->addMethodCall('addExtension', array(new \AVCMS\Core\View\TwigModuleExtension()))
            ->addMethodCall('addExtension', array(new \AVCMS\Core\Form\Twig\FormExtension()))
            ->addMethodCall('addExtension', array(new MarkdownExtension(new MichelfMarkdownEngine())))
            ->addMethodCall('addExtension', array(new Reference('twig.routing.extension')))
            ->addMethodCall('addExtension', array(new Reference('twig.asset_manager.extension')))
            ->addMethodCall('addExtension', array(new Reference('twig.http.kernel.extension')))
            ->addMethodCall('addExtension', array(new Reference('twig.translation.extension')))
            ->addMethodCall('addExtension', array(new \Symfony\Bridge\Twig\Extension\CodeExtension(null, '/', 'UTF-8')))
        ;

        $container->register('twig.filesystem', 'AVCMS\Core\View\TwigLoaderFilesystem')
            ->setArguments(array('templates', array('original.twig' => 'replacement.twig')))
            ->addMethodCall('addPath', array('templates/admin/avcms', 'admin'))
            ->addMethodCall('addPath', array('templates/dev/avcms', 'dev'))
        ;

        $container->register('twig.http.kernel.extension', 'Symfony\Bridge\Twig\Extension\HttpKernelExtension')
            ->setArguments(array(new Reference('fragment_handler')))
        ;

        $container->register('twig.routing.extension', 'Symfony\Bridge\Twig\Extension\RoutingExtension')
            ->setArguments(array(new Reference('routing.url_generator')));

        $container->register('twig.translation.extension', 'Symfony\Bridge\Twig\Extension\TranslationExtension')
            ->setArguments(array(new Reference('translator')));

        $container->register('twig.asset_manager.extension', 'AVCMS\Core\AssetManager\Twig\AssetManagerExtension')
            ->setArguments(array('%dev_mode%'));

        if ($container->getParameter('dev_mode') == true) {
            $container->register('twig.asset_manager.extension.developer', 'AVCMS\Core\AssetManager\Twig\AssetManagerExtension')
                ->setArguments(array('%dev_mode%', new Reference('asset_manager')))
                ->setDecoratedService('twig.asset_manager.extension')
            ;
        }
    }
} 