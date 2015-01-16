<?php
/**
 * User: Andy
 * Date: 16/01/15
 * Time: 11:18
 */

namespace AVCMS\Bundles\Sitemaps\Services;

use AV\Service\ServicesInterface;
use AVCMS\Bundles\Sitemaps\Services\Compiler\SitemapCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SitemapServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->addCompilerPass(new SitemapCompilerPass());

        $container->register('sitemaps_manager', 'AVCMS\Core\Sitemaps\SitemapsManager')
            ->setArguments([new Reference('sitemap_writer'), '%cache_dir%'])
        ;

        $container->register('sitemap_writer', 'AVCMS\Core\Sitemaps\SitemapWriter')
            ->setFactory([new Reference('sitemap_writer_factory'), 'create'])
        ;

        $container->register('sitemap_writer_factory', 'AVCMS\Bundles\Sitemaps\Services\SitemapWriterFactory')
            ->setArguments([new Reference('site_url'), '%root_dir%/%web_path%/sitemaps', '%web_path%/sitemaps'])
        ;
    }
}
