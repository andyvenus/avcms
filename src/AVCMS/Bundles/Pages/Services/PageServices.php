<?php
/**
 * User: Andy
 * Date: 23/01/15
 * Time: 15:10
 */

namespace AVCMS\Bundles\Pages\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PageServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('pages.model', 'AVCMS\Bundles\Pages\Model\Pages')
            ->addTag('model')
        ;

        $container->register('page.menu_item_type', 'AVCMS\Bundles\Pages\MenuItem\PageMenuItemType')
            ->setArguments([new Reference('pages.model'), new Reference('router')])
            ->addTag('menu.item_type', ['id' => 'page'])
        ;

        $container->register('sitemap.pages', 'AVCMS\Core\Sitemaps\ContentSitemap')
            ->setArguments([new Reference('pages.model'), new Reference('router'), 'page'])
            ->addTag('sitemap')
        ;
    }
}
