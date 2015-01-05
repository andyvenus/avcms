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
        $container->register('wallpaper.twig.extension', 'AVCMS\Bundles\Wallpapers\Twig\WallpaperTwigExtension')
            ->setArguments([new Reference('router')])
            ->addTag('twig.extension')
        ;

        $container->register('wallpaper.resolutions_manager', 'AVCMS\Bundles\Wallpapers\ResolutionsManager\ResolutionsManager')
            ->setArguments(['%root_dir%'])
        ;

        $container->register('wallpaper.categories_model', 'AVCMS\Bundles\Wallpapers\Model\WallpaperCategories')
            ->setArguments(array('AVCMS\Bundles\Wallpapers\Model\WallpaperCategories'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('menu_types.wallpaper_categories', 'AVCMS\Bundles\Categories\MenuItemType\CategoriesMenuItemType')
            ->setArguments([new Reference('wallpaper.categories_model'), new Reference('router')])
            ->addTag('menu.item_type', ['id' => 'wallpaper_categories'])
        ;

    }
}
