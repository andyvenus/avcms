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
            ->setArguments([new Reference('router'), new Reference('settings_manager')])
            ->addTag('twig.extension')
        ;

        $container->register('wallpaper.resolutions_manager', 'AVCMS\Bundles\Wallpapers\ResolutionsManager\ResolutionsManager')
            ->setArguments(['%root_dir%', new Reference('settings_manager'), '%cache_dir%'])
        ;

        $container->register('wallpaper.categories_model', 'AVCMS\Bundles\Wallpapers\Model\WallpaperCategories')
            ->addTag('model')
        ;

        $container->register('menu_types.wallpaper_categories', 'AVCMS\Bundles\Categories\MenuItemType\CategoriesMenuItemType')
            ->setArguments([new Reference('wallpaper.categories_model'), new Reference('router'), 'wallpaper_category'])
            ->addMethodCall('setName', ['Wallpaper Categories'])
            ->addTag('menu.item_type', ['id' => 'wallpaper_categories'])
        ;

        /*
        $container->register('menu_types.wallpaper_resolutions', 'AVCMS\Bundles\Wallpapers\MenuItemType\WallpaperResolutionsMenuItemType')
            ->setArguments([new Reference('wallpaper.resolutions_manager'), new Reference('router'), 'wallpaper_browse_resolution'])
            ->addTag('menu.item_type', ['id' => 'wallpaper_resolutions'])
        ;
        */

        $container->register('subscriber.wallpaper_cache_outlet', 'AVCMS\Bundles\Wallpapers\EventSubscriber\WallpaperCacheOutletSubscriber')
            ->setArguments([new Reference('translator')])
            ->addTag('event.subscriber')
        ;

        $container->register('subscriber.wallpaper_cache_limiter', 'AVCMS\Bundles\Wallpapers\EventSubscriber\WallpaperCacheLimiterSubscriber')
            ->setArguments(['%root_dir%', '%web_path%', new Reference('bundle_manager'), new Reference('settings_manager')])
            ->addTag('event.subscriber')
        ;

        $container->register('sitemap.wallpapers', 'AVCMS\Core\Sitemaps\ContentSitemap')
            ->setArguments([new Reference('wallpapers.model'), new Reference('router'), 'wallpaper_details'])
            ->addTag('sitemap')
        ;

        $container->register('model.wallpaper_submissions', 'AVCMS\Bundles\Wallpapers\Model\WallpaperSubmissions')
            ->addTag('model')
        ;

        $container->register('subscriber.wallpaper_submissions_menu_item', 'AVCMS\Bundles\Wallpapers\EventSubscriber\WallpaperSubmissionsMenuItemSubscriber')
            ->setArguments([new Reference('model.wallpaper_submissions')])
            ->addTag('event.subscriber')
        ;
    }
}
