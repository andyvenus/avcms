<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:41
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class Taxonomy implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('taxonomy_manager', 'AVCMS\Core\Taxonomy\ContainerAwareTaxonomyManager')
            ->setArguments(array('%container%'))
            ->addMethodCall('addContainerTaxonomy', array('tags', 'taxonomy.tags'))
        ;
    }
}