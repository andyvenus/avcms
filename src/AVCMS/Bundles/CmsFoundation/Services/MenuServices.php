<?php
/**
 * User: Andy
 * Date: 29/08/2014
 * Time: 10:44
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MenuServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('menu_manager', 'AVCMS\Core\Menu\MenuManager')
            ->setArguments(array(new Reference('router'), new Reference('menu_manager.model'), new Reference('menu_manager.items_model')))
        ;

        $container->register('menu_manager.model', 'AVCMS\Bundles\CmsFoundation\Model\Menus')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\Menus'))
            ->setFactoryService('model_factory')
            ->setFactoryMethod('create')
        ;

        $container->register('menu_manager.items_model', 'AVCMS\Bundles\CmsFoundation\Model\MenuItems')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\MenuItems'))
            ->setFactoryService('model_factory')
            ->setFactoryMethod('create')
        ;

        $container->register('menu_manager.subscriber', 'AVCMS\Bundles\CmsFoundation\Subscribers\UpdateMenusSubscriber')
            ->setArguments(array(new Reference('menu_manager'), new Reference('bundle_manager')))
            ->addTag('event.subscriber')
        ;

        $container->register('twig.menu_manager.extension', 'AVCMS\Core\Menu\TwigExtension\MenuManagerTwigExtension')
            ->setArguments(array(new Reference('menu_manager')))
            ->addTag('twig.extension')
        ;
    }
}