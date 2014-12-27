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
    }
}
