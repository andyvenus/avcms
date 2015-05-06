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

        $container->register('template_choices_provider', 'AVCMS\Bundles\CmsFoundation\Settings\TemplateChoicesProvider')
            ->setArguments(['%root_dir%', 'webmaster/templates/frontend'])
        ;

        $container->register('email_template_choices_provider', 'AVCMS\Bundles\CmsFoundation\Settings\TemplateChoicesProvider')
            ->setArguments(['%root_dir%', 'webmaster/templates/email', false])
        ;

        $container->register('assets.loader.template', 'AVCMS\Core\View\AssetLoader\TemplateAssetLoader')
            ->setArguments(array(new Reference('template_manager')))
        ;

        $container->register('bundle.resource_locator', 'AVCMS\Core\Bundle\ResourceLocator')
            ->setArguments(array(new Reference('bundle_manager'), new Reference('settings_manager'), '%root_dir%', '%app_dir%'))
        ;

        $container->register('twig.filesystem', 'AVCMS\Core\View\TwigLoaderFilesystem')
            ->setArguments(array(new Reference('bundle.resource_locator'), new Reference('settings_manager'), '%root_dir%'))
            ->addMethodCall('addPath', array('%root_dir%/src/AVCMS/Bundles/Admin/resources/templates', 'admin'))
            ->addMethodCall('addPath', array('%root_dir%/webmaster/templates', 'templates'))
        ;

        $container->register('twig.extension.pagination', 'AVCMS\Bundles\CmsFoundation\Twig\PaginationTwigExtension')
            ->setArguments([new Reference('request.stack')])
            ->addTag('twig.extension')
        ;

        $container->register('twig.extension.search', 'AVCMS\Bundles\CmsFoundation\Twig\SearchTwigExtension')
            ->setArguments([new Reference('bundle_manager'), new Reference('form.builder'), new Reference('router'), new Reference('translator'), new Reference('request.stack')])
            ->addTag('twig.extension')
        ;

        $container->register('twig.extension.flash_messages', 'AVCMS\Bundles\CmsFoundation\Twig\FlashMessagesTwigExtension')
            ->setArguments([new Reference('session')])
            ->addMethodCall('addMessagesProvider', [new Reference('user_validation_messages')])
            ->addTag('twig.extension')
        ;

        $container->register('user_validation_messages', 'AVCMS\Bundles\CmsFoundation\Twig\Messages\UserValidationMessages')
            ->setArguments([new Reference('security.token_storage'), new Reference('translator'), new Reference('router')])
        ;

        $container->register('twig.extension.outlet', 'AVCMS\Bundles\CmsFoundation\Twig\TwigOutletExtension')
            ->setArguments([new Reference('dispatcher')])
            ->addTag('twig.extension')
        ;

        $container->register('twig.extension.php_include', 'AVCMS\Bundles\CmsFoundation\Twig\PhpIncludeTwigExtension')
            ->addTag('twig.extension')
        ;
    }
}
