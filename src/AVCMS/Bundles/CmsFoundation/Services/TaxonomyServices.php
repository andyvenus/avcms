<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:41
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TaxonomyServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('taxonomy_manager', 'AVCMS\Core\Taxonomy\ContainerAwareTaxonomyManager')
            ->setArguments([new Reference('service_container')])
            ->addMethodCall('addContainerTaxonomy', ['tags', 'taxonomy.tags'])
        ;

        $container->register('taxonomy.injector', 'AVCMS\Bundles\CmsFoundation\Subscribers\TaxonomyInjectorSubscriber')
            ->setArguments([new Reference('taxonomy_manager')])
            ->addTag('event.subscriber')
        ;
    }
}