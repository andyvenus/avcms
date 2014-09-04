<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:41
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaxonomyServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('taxonomy_manager', 'AVCMS\Core\Taxonomy\ContainerAwareTaxonomyManager')
            ->setArguments(array(new Reference('service_container')))
            ->addMethodCall('addContainerTaxonomy', array('tags', 'taxonomy.tags'))
        ;
    }
}