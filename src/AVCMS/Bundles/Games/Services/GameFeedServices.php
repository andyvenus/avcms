<?php
/**
 * User: Andy
 * Date: 08/02/15
 * Time: 14:33
 */

namespace AVCMS\Bundles\Games\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GameFeedServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('game_feed_downloader', 'AVCMS\Bundles\Games\GameFeeds\Downloader\GameFeedDownloader')
            ->setArguments([new Reference('settings_manager'), new Reference('feed_games.model')])
            ->addMethodCall('addFeed', [new Reference('feed.fgd')])
            ->addMethodCall('addFeed', [new Reference('feed.kongregate')])
            ->addMethodCall('addFeed', [new Reference('feed.spil_games')])
            ->addMethodCall('addFeed', [new Reference('feed.free_online_games')])
        ;

        $container->register('feed_games.model', 'AVCMS\Bundles\Games\Model\FeedGames')
            ->addTag('model')
        ;

        $container->register('feed.fgd', 'AVCMS\Bundles\Games\GameFeeds\FlashGameDistributionFeed');

        $container->register('feed.kongregate', 'AVCMS\Bundles\Games\GameFeeds\KongregateFeed');

        $container->register('feed.spil_games', 'AVCMS\Bundles\Games\GameFeeds\SpilGamesFeed');

        $container->register('feed.free_online_games', 'AVCMS\Bundles\Games\GameFeeds\FreeOnlineGamesFeed');
    }
}
