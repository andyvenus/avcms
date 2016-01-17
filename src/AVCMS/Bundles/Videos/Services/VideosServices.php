<?php
/**
 * User: Andy
 * Date: 06/02/15
 * Time: 21:40
 */

namespace AVCMS\Bundles\Videos\Services;

use AV\Service\ServicesInterface;
use AVCMS\Bundles\Videos\Services\CompilerPass\VideoTypesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class VideosServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->addCompilerPass(new VideoTypesCompilerPass());

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
        ;

        $container->register('video.type.youtube', 'AVCMS\Bundles\Videos\Type\YouTubeVideo')
            ->setArguments([new Reference('session')])
            ->addTag('video_type')
        ;

        $container->register('video.type.vimeo', 'AVCMS\Bundles\Videos\Type\VimeoVideo')->addTag('video_type');

        $container->register('video.type.dailymotion', 'AVCMS\Bundles\Videos\Type\DailymotionVideo')->addTag('video_type');

        $container->register('video.type.metacafe', 'AVCMS\Bundles\Videos\Type\MetacafeVideo')->addTag('video_type');

        $container->register('video.type.vine', 'AVCMS\Bundles\Videos\Type\VineVideo')->addTag('video_type');

        $container->register('video_category_choices', 'AVCMS\Bundles\Categories\Form\ChoicesProvider\CategoryChoicesProvider')
            ->setArguments([new Reference('videos.categories_model')])
        ;
    }
}
