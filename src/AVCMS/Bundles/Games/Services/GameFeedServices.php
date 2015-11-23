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
            ->addMethodCall('addFeed', [new Reference('feed.arcade_game_feed')])
            ->addMethodCall('addFeed', [new Reference('feed.2_player_games')])
            //->addMethodCall('addFeed', [new Reference('feed.unity_feeds')]) Unity Feeds have shuttered
            ->addMethodCall('addFeed', [new Reference('feed.famobi')])
        ;

        $container->register('feed_games.model', 'AVCMS\Bundles\Games\Model\FeedGames')
            ->addTag('model')
        ;

        $container->register('feed.fgd', 'AVCMS\Bundles\Games\GameFeeds\FlashGameDistributionFeed');

        $container->register('feed.kongregate', 'AVCMS\Bundles\Games\GameFeeds\KongregateFeed');

        $container->register('feed.spil_games', 'AVCMS\Bundles\Games\GameFeeds\SpilGamesFeed');

        $container->register('feed.free_online_games', 'AVCMS\Bundles\Games\GameFeeds\FreeOnlineGamesFeed');

        $container->register('feed.arcade_game_feed', 'AVCMS\Bundles\Games\GameFeeds\ArcadeGameFeed');

        $container->register('feed.2_player_games', 'AVCMS\Bundles\Games\GameFeeds\TwoPlayerGamesFeed');

        $container->register('feed.unity_feeds', 'AVCMS\Bundles\Games\GameFeeds\UnityFeedsFeed');

        $container->register('feed.famobi', 'AVCMS\Bundles\Games\GameFeeds\FamobiFeed');
    }
}
