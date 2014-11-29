<?php
/**
 * User: Andy
 * Date: 08/09/2014
 * Time: 14:06
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class ModuleServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('module_manager', 'AVCMS\Core\Module\ModuleManager')
            ->setArguments(array(new Reference('fragment_handler'), new Reference('module.model'), new Reference('module_positions_manager'), new Reference('request.stack'), new Reference('security.context'), '%cache_dir%/modules'))
            ->addMethodCall('setProvider', array(new Reference('module.bundle_provider')))
        ;

        $container->register('module.bundle_provider', 'AVCMS\Core\Bundle\ModuleProvider\BundleModulesProvider')
            ->setArguments(array(new Reference('bundle_manager')))
        ;

        $container->register('module.model', 'AVCMS\Bundles\CmsFoundation\Model\Modules')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\Modules'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('module.positions_model', 'AVCMS\Bundles\CmsFoundation\Model\ModulePositions')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\ModulePositions'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('modules.twig_extension', 'AVCMS\Core\Module\Twig\ModuleManagerTwigExtension')
            ->setArguments(array(new Reference('module_manager')))
            ->addTag('twig.extension')
        ;

        $container->register('module_positions_manager', 'AVCMS\Core\Module\ModulePositionsManager')
            ->setArguments(array(new Reference('module.positions_model')))
            ->addMethodCall('setProvider', [new Reference('module.bundle_positions_provider')])
            ->addMethodCall('setProvider', [new Reference('module.template_positions_provider')])
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'updatePositions'])
        ;

        $container->register('module.bundle_positions_provider', 'AVCMS\Core\Bundle\ModuleProvider\BundleModulePositionsProvider')
            ->setArguments([new Reference('bundle_manager')])
        ;

        $container->register('module.template_positions_provider', 'AVCMS\Core\View\ModuleProvider\TemplateModulePositionsProvider')
            ->setArguments([new Reference('template_manager')])
        ;

        $container->register('module.cache_buster_subscriber', 'AVCMS\Bundles\CmsFoundation\Subscribers\ModuleCacheBusterSubscriber')
            ->setArguments([new Reference('bundle_manager'), '%cache_dir%/modules'])
            ->addTag('event.subscriber')
        ;
    }
}