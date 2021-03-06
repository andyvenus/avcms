<?php
/**
 * User: Andy
 * Date: 19/11/14
 * Time: 20:24
 */

namespace AVCMS\Bundles\CmsFoundation\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HitCounterServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('hits.model', 'AVCMS\Bundles\CmsFoundation\Model\Hits')
            ->addTag('model')
        ;

        $container->register('hitcounter', 'AVCMS\Core\HitCounter\HitCounter')
            ->setArguments([new Reference('hits.model'), new Reference('request.stack')])
        ;
    }
}
