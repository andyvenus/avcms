<?php
/**
 * User: Andy
 * Date: 19/10/14
 * Time: 23:24
 */

namespace AVCMS\Bundles\Blog\Services;

use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MarkdownServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('markdown_engine', 'Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine');

        $container->register('twig.markdown_extension', 'Aptoma\Twig\Extension\MarkdownExtension')
            ->setArguments(array(new Reference('markdown_engine')))
            ->addTag('twig.extension')
        ;
    }
}