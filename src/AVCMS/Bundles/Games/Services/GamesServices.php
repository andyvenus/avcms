<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:40
 */

namespace AVCMS\Bundles\Games\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GamesServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('game_embeds.model', 'AVCMS\Bundles\Games\Model\GameEmbeds')
            ->addTag('model')
        ;

        $container->register('twig_extension.embed_game', 'AVCMS\Bundles\Games\TwigExtension\EmbedGameTwigExtension')
            ->setArguments([new Reference('game_embeds.model'), new Reference('site_url'), '%games_dir%', '%game_thumbnails_dir%'])
            ->addTag('twig.extension')
        ;

        $container->register('games.model', 'AVCMS\Bundles\Games\Model\Games')
            ->addTag('model')
        ;

        $container->register('games.categories_model', 'AVCMS\Bundles\Games\Model\GameCategories')
            ->addTag('model')
        ;

        $container->register('sitemap.games', 'AVCMS\Core\Sitemaps\ContentSitemap')
            ->setArguments([new Reference('games.model'), new Reference('router'), 'play_game'])
            ->addTag('sitemap')
        ;

        $container->register('menu_types.game_categories', 'AVCMS\Bundles\Categories\MenuItemType\CategoriesMenuItemType')
            ->setArguments([new Reference('games.categories_model'), new Reference('router'), 'game_category'])
            ->addMethodCall('setName', ['Game Categories'])
            ->addTag('menu.item_type', ['id' => 'game_categories'])
        ;
    }
}
