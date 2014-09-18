<?php
/**
 * User: Andy
 * Date: 08/09/2014
 * Time: 14:06
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ModuleServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('module_manager', 'AVCMS\Core\Module\ModuleManager')
            ->setArguments(array(new Reference('fragment_handler'), new Reference('module.model'), new Reference('module.positions_model'), new Reference('request.stack'), 'cache'))
            ->addMethodCall('setProvider', array(new Reference('module.bundle_provider')))
        ;

        $container->register('module.bundle_provider', 'AVCMS\Core\Bundle\ModuleProvider\BundleModulesProvider')
            ->setArguments(array(new Reference('bundle_manager')))
        ;

        $container->register('module.model', 'AVCMS\Bundles\CmsFoundation\Model\Modules')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\Modules'))
            ->setFactoryService('model_factory')
            ->setFactoryMethod('create')
        ;

        $container->register('module.positions_model', 'AVCMS\Bundles\CmsFoundation\Model\ModulePositions')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\ModulePositions'))
            ->setFactoryService('model_factory')
            ->setFactoryMethod('create')
        ;

        $container->register('modules.twig_extension', 'AVCMS\Core\Module\Twig\ModuleManagerTwigExtension')
            ->setArguments(array(new Reference('module_manager')))
            ->addTag('twig.extension')
        ;
    }
}