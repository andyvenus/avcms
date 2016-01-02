<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:40
 */

namespace AVCMS\Bundles\Videos\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class VideosServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('twig_extension.videos', 'AVCMS\Bundles\Videos\TwigExtension\VideosTwigExtension')
            ->setArguments([new Reference('site_url'), '%videos_dir%', '%video_thumbnails_dir%'])
            ->addTag('twig.extension')
        ;

        $container->register('videos.model', 'AVCMS\Bundles\Videos\Model\Videos')
            ->addTag('model')
        ;

        $container->register('videos.categories_model', 'AVCMS\Bundles\Videos\Model\VideoCategories')
            ->addTag('model')
        ;

        $container->register('sitemap.videos', 'AVCMS\Core\Sitemaps\ContentSitemap')
            ->setArguments([new Reference('videos.model'), new Reference('router'), 'watch_video'])
            ->addTag('sitemap')
        ;

        $container->register('menu_types.video_categories', 'AVCMS\Bundles\Categories\MenuItemType\CategoriesMenuItemType')
            ->setArguments([new Reference('videos.categories_model'), new Reference('router'), 'video_category'])
            ->addMethodCall('setName', ['Video Categories'])
            ->addTag('menu.item_type', ['id' => 'video_categories'])
        ;

        $container->register('videos.submissions_model', 'AVCMS\Bundles\Videos\Model\VideoSubmissions')
            ->addTag('model')
        ;

        $container->register('subscriber.video_submissions_menu_item', 'AVCMS\Bundles\Videos\EventSubscriber\VideoSubmissionsMenuItemSubscriber')
            ->setArguments([new Reference('videos.submissions_model')])
            ->addTag('event.subscriber')
        ;

        $container->register('video_manager', 'AVCMS\Bundles\Videos\Type\VideoManager')
            ->setArguments([new Reference('videos.model')])
            ->addMethodCall('addType', [new Reference('video.type.youtube')])
            ->addMethodCall('addType', [new Reference('video.type.vimeo')])
        ;

        $container->register('video.type.youtube', 'AVCMS\Bundles\Videos\Type\YouTubeVideo')
            ->setArguments([new Reference('session')])
        ;

        $container->register('video.type.vimeo', 'AVCMS\Bundles\Videos\Type\VimeoVideo');

        $container->register('video_category_choices', 'AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider')
            ->setArguments([new Reference('videos.categories_model')])
        ;
    }
}
