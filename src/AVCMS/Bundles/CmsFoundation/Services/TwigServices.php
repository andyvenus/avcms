<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:02
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

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

        $container->register('twig.http.kernel.extension', 'Symfony\Bridge\Twig\Extension\HttpKernelExtension')
            ->setArguments(array(new Reference('fragment_handler')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.routing.extension', 'Symfony\Bridge\Twig\Extension\RoutingExtension')
            ->setArguments(array(new Reference('router')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.translation.extension', 'Symfony\Bridge\Twig\Extension\TranslationExtension')
            ->setArguments(array(new Reference('translator')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.form.extension', 'AV\Form\Twig\FormExtension')
            ->addTag('twig.extension')
        ;

        $container->register('twig.markdown.extension', 'Aptoma\Twig\Extension\MarkdownExtension')
            ->setArguments(array(new Reference('markdown_engine')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.code.extension', 'Symfony\Bridge\Twig\Extension\CodeExtension')
            ->setArguments(array(null, '/', 'UTF-8'))
            ->addTag('twig.extension')
        ;

        $container->register('twig.globals.extension', 'AVCMS\Core\View\Extension\GlobalVarsTwigExtension')
            ->setArguments(array(new Reference('request.stack')))
            ->addTag('twig.extension')
        ;


        $container->register('twig.asset_manager.extension', 'AVCMS\Core\AssetManager\Twig\AssetManagerExtension')
            ->setArguments(array('%dev_mode%'))
            ->addTag('twig.extension')
        ;

        $container->register('twig.security.extension', 'Symfony\Bridge\Twig\Extension\SecurityExtension')
            ->setArguments(array(new Reference('security.context')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.optimiser_extension', 'Twig_Extension_Optimizer')
            ->addTag('twig.extension.disabledad')
        ;

        $container->register('markdown_engine', 'Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine');

        if ($container->getParameter('dev_mode') == true) {
            $container->register('twig.asset_manager.extension.developer', 'AVCMS\Core\AssetManager\Twig\AssetManagerExtension')
                ->setArguments(array('%dev_mode%', new Reference('asset_manager')))
                ->setDecoratedService('twig.asset_manager.extension')
                ->addTag('twig.extension')
            ;
        }


        $container->addCompilerPass(new TwigEnvironmentPass());
    }
} 