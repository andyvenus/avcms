<?php
/**
 * User: Andy
 * Date: 29/08/2014
 * Time: 10:44
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MenuServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('menu_manager', 'AVCMS\Core\Menu\MenuManager')
            ->setArguments(array(new Reference('router'), new Reference('menu_manager.model'), new Reference('menu_manager.items_model'), new Reference('security.context'), new Reference('translator')))
            ->addMethodCall('addMenuItemType', [new Reference('menu_type.route'), 'route'])
            ->addMethodCall('addMenuItemType', [new Reference('menu_type.url'), 'url'])
        ;

        $container->register('menu_type.route', 'AVCMS\Core\Menu\MenuItemType\RouteMenuItemType')
            ->setArguments([new Reference('router')])
        ;

        $container->register('menu_type.url', 'AVCMS\Core\Menu\MenuItemType\UrlMenuItemType');

        $container->register('menu_manager.model', 'AVCMS\Bundles\CmsFoundation\Model\Menus')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\Menus'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('menu_manager.items_model', 'AVCMS\Bundles\CmsFoundation\Model\MenuItems')
            ->setArguments(array('AVCMS\Bundles\CmsFoundation\Model\MenuItems'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('menu_manager.subscriber', 'AVCMS\Bundles\CmsFoundation\Subscribers\UpdateMenusSubscriber')
            ->setArguments(array(new Reference('menu_manager'), new Reference('template_manager'), new Reference('bundle_manager')))
            ->addTag('event.subscriber')
        ;

        $container->register('twig.menu_manager.extension', 'AVCMS\Core\Menu\TwigExtension\MenuManagerTwigExtension')
            ->setArguments(array(new Reference('menu_manager')))
            ->addTag('twig.extension')
        ;
    }
}
