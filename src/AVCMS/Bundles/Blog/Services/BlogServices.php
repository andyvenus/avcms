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
            ->setArguments(['AVCMS\Bundles\Blog\Model\BlogPosts'])
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('sitemap.blog_posts', 'AVCMS\Bundles\Blog\Sitemap\BlogSitemap')
            ->setArguments([new Reference('blog.posts_model'), new Reference('router')])
            ->addTag('sitemap')
        ;
    }
}
