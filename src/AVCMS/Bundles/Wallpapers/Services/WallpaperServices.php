<?php
/**
 * User: Andy
 * Date: 27/12/14
 * Time: 13:34
 */

namespace AVCMS\Bundles\Wallpapers\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WallpaperServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('wallpapers.model', 'AVCMS\Bundles\Wallpapers\Model\Wallpapers')
            ->addTag('model')
        ;

        $container->register('wallpaper.twig.extension', 'AVCMS\Bundles\Wallpapers\Twig\WallpaperTwigExtension')
            ->setArguments([new Reference('router')])
            ->addTag('twig.extension')
        ;

        $container->register('wallpaper.resolutions_manager', 'AVCMS\Bundles\Wallpapers\ResolutionsManager\ResolutionsManager')
            ->setArguments(['%root_dir%', new Reference('settings_manager')])
        ;

        $container->register('wallpaper.categories_model', 'AVCMS\Bundles\Wallpapers\Model\WallpaperCategories')
            ->addTag('model')
        ;

        $container->register('menu_types.wallpaper_categories', 'AVCMS\Bundles\Categories\MenuItemType\CategoriesMenuItemType')
            ->setArguments([new Reference('wallpaper.categories_model'), new Reference('router'), 'wallpaper_category'])
            ->addMethodCall('setName', ['Wallpaper Categories'])
            ->addTag('menu.item_type', ['id' => 'wallpaper_categories'])
        ;

        $container->register('subscriber.wallpaper_module_cache_buster', 'AVCMS\Bundles\Wallpapers\EventSubscriber\WallpaperModuleCacheBusterSubscriber')
            ->setArguments([new Reference('module_manager')])
            ->addTag('event.subscriber')
        ;

        $container->register('subscriber.wallpaper_cache_outlet', 'AVCMS\Bundles\Wallpapers\EventSubscriber\WallpaperCacheOutletSubscriber')
            ->setArguments([new Reference('translator')])
            ->addTag('event.subscriber')
        ;

        $container->register('subscriber.wallpaper_cache_limiter', 'AVCMS\Bundles\Wallpapers\EventSubscriber\WallpaperCacheLimiterSubscriber')
            ->setArguments(['%root_dir%', '%web_path%', new Reference('bundle_manager'), new Reference('settings_manager')])
            ->addTag('event.subscriber')
        ;

        $container->register('sitemap.wallpapers', 'AVCMS\Bundles\Wallpapers\Sitemap\WallpapersSitemap')
            ->setArguments([new Reference('wallpapers.model'), new Reference('router')])
            ->addTag('sitemap')
        ;
    }
}
