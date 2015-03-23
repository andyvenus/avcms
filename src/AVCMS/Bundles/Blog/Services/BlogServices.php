<?php
/**
 * User: Andy
 * Date: 23/01/15
 * Time: 16:01
 */

namespace AVCMS\Bundles\Blog\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class BlogServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('blog.posts_model', 'AVCMS\Bundles\Blog\Model\BlogPosts')
            ->addTag('model')
        ;

        $container->register('blog.categories_model', 'AVCMS\Bundles\Blog\Model\BlogCategories')
            ->addTag('model')
        ;

        $container->register('sitemap.blog_posts', 'AVCMS\Core\Sitemaps\ContentSitemap')
            ->setArguments([new Reference('blog.posts_model'), new Reference('router'), 'blog_post'])
            ->addTag('sitemap')
        ;

        $container->register('menu_types.blog_categories', 'AVCMS\Bundles\Categories\MenuItemType\CategoriesMenuItemType')
            ->setArguments([new Reference('blog.categories_model'), new Reference('router'), 'blog_category'])
            ->addMethodCall('setName', ['Blog Categories'])
            ->addTag('menu.item_type', ['id' => 'blog_categories'])
        ;
    }
}
