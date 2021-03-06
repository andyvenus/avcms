<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 14:41
 */

namespace AVCMS\Bundles\Tags\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TagsServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('taxonomy.tags', 'AVCMS\Bundles\Tags\Taxonomy\TagsTaxonomy')
            ->setArguments(array(new Reference('tags.model'), new Reference('taxonomy.tags.model')))
        ;

        $container->register('tags.model', 'AVCMS\Bundles\Tags\Model\TagsModel')
            ->addTag('model')
        ;

        $container->register('taxonomy.tags.model', 'AVCMS\Bundles\Tags\Model\TagsTaxonomyModel')
            ->addTag('model')
        ;

        $container->register('listener.update_tags', 'AVCMS\Bundles\Tags\Events\UpdateTagsSubscriber')
            ->setArguments(array(new Reference('taxonomy_manager')))
            ->addTag('event.subscriber')
        ;
    }
}
