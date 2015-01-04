<?php
/**
 * User: Andy
 * Date: 04/01/15
 * Time: 20:57
 */

namespace AVCMS\Bundles\LikeDislike\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RatingsServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('ratings.model', 'AVCMS\Bundles\LikeDislike\Model\Ratings')
            ->setArguments(array('AVCMS\Bundles\LikeDislike\Model\Ratings'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('ratings_manager', 'AVCMS\Bundles\LikeDislike\RatingsManager\RatingsManager')
            ->setArguments([new Reference('bundle_manager'), new Reference('ratings.model'), new Reference('security.token_storage'), new Reference('model_factory')])
        ;

        $container->register('ratings.twig_extension', 'AVCMS\Bundles\LikeDislike\TwigExtension\LikeDislikeTwigExtension')
            ->setArguments([new Reference('ratings_manager'), new Reference('security.token_storage')])
            ->addTag('twig.extension')
        ;
    }
}
