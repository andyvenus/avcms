<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:40
 */

namespace AVCMS\Bundles\Images\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImageServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('images.helper', 'AVCMS\Bundles\Images\ImagesHelper\ImagesHelper')
            ->setArguments([new Reference('site_url'), '%images_dir%'])
        ;

        $container->register('twig_extension.images', 'AVCMS\Bundles\Images\TwigExtension\ImagesTwigExtension')
            ->setArguments([new Reference('router'), new Reference('images.helper')])
            ->addTag('twig.extension')
        ;

        $container->register('images.model', 'AVCMS\Bundles\Images\Model\ImageCollections')
            ->addTag('model')
        ;

        $container->register('images.categories_model', 'AVCMS\Bundles\Images\Model\ImageCategories')
            ->addTag('model')
        ;

        $container->register('sitemap.images', 'AVCMS\Core\Sitemaps\ContentSitemap')
            ->setArguments([new Reference('images.model'), new Reference('router'), 'image_collection'])
            ->addTag('sitemap')
        ;

        $container->register('menu_types.image_categories', 'AVCMS\Bundles\Categories\MenuItemType\CategoriesMenuItemType')
            ->setArguments([new Reference('images.categories_model'), new Reference('router'), 'image_category'])
            ->addMethodCall('setName', ['Image Categories'])
            ->addTag('menu.item_type', ['id' => 'image_categories'])
        ;

        $container->register('images.submissions_model', 'AVCMS\Bundles\Images\Model\ImageSubmissions')
            ->addTag('model')
        ;

        $container->register('subscriber.image_submissions_menu_item', 'AVCMS\Bundles\Images\EventSubscriber\ImageSubmissionsMenuItemSubscriber')
            ->setArguments([new Reference('images.submissions_model')])
            ->addTag('event.subscriber')
        ;

        $container->register('subscriber.image_cache_button', 'AVCMS\Bundles\Images\EventSubscriber\ImageCacheOutletSubscriber')
            ->setArguments([new Reference('translator')])
            ->addTag('event.subscriber')
        ;
    }
}
