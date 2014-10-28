<?php
/**
 * User: Andy
 * Date: 28/10/14
 * Time: 11:25
 */

namespace AVCMS\Bundles\Comments\Services;

use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CommentServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('comments.model', 'AVCMS\Bundles\Comments\Model\Comments')
            ->setArguments(['AVCMS\Bundles\Comments\Model\Comments'])
            ->setFactoryService('model_factory')
            ->setFactoryMethod('create')
        ;

        $container->register('twig.comments_extension', 'AVCMS\Bundles\Comments\Twig\CommentsTwigExtension')
            ->setArguments([new Reference('comments.model')])
            ->addTag('twig.extension')
        ;
    }
}