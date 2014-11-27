<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 12:00
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TemplateServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('template_manager', 'AVCMS\Core\View\TemplateManager')
            ->setArguments(array(new Reference('settings_manager'), '%dev_mode%'))
        ;

        $container->register('assets.loader.template', 'AVCMS\Core\View\AssetLoader\TemplateAssetLoader')
            ->setArguments(array(new Reference('template_manager')))
        ;

        $container->register('bundle.resource_locator', 'AVCMS\Core\Bundle\ResourceLocator')
            ->setArguments(array(new Reference('bundle_manager'), new Reference('settings_manager'), '%root_dir%', '%app_dir%'))
        ;

        $container->register('twig.filesystem', 'AVCMS\Core\View\TwigLoaderFilesystem')
            ->setArguments(array(new Reference('bundle.resource_locator'), new Reference('settings_manager'), '%root_dir%'))
            ->addMethodCall('addPath', array('%root_dir%/templates/admin/avcms', 'admin'))
            ->addMethodCall('addPath', array('%root_dir%/templates/dev/avcms', 'dev'))
            ->addMethodCall('addPath', array('%root_dir%/templates/email/avcms', 'email'))
        ;

        $container->register('twig.extension.pagination', 'AVCMS\Bundles\CmsFoundation\Twig\PaginationTwigExtension')
            ->setArguments([new Reference('request.stack')])
            ->addTag('twig.extension')
        ;

        $container->register('twig.extension.search', 'AVCMS\Bundles\CmsFoundation\Twig\SearchTwigExtension')
            ->setArguments([new Reference('bundle_manager'), new Reference('form.builder'), new Reference('router'), new Reference('translator'), new Reference('request.stack')])
            ->addTag('twig.extension')
        ;
    }
}