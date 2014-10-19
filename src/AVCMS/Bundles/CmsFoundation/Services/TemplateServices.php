<?php
/**
 * User: Andy
 * Date: 22/08/2014
 * Time: 12:00
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TemplateServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('template_manager', 'AVCMS\Core\View\TemplateManager')
            ->setArguments(array(new Reference('settings_manager')))
        ;

        $container->register('assets.loader.template', 'AVCMS\Core\View\AssetLoader\TemplateAssetLoader')
            ->setArguments(array(new Reference('template_manager')))
        ;

        $container->register('settings.loader.template', 'AVCMS\Core\View\SettingsLoader\TemplateSettingsLoader')
            ->setArguments(array(new Reference('template_manager')))
        ;
    }
}