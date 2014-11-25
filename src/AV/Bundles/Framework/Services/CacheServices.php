<?php
/**
 * User: Andy
 * Date: 25/11/14
 * Time: 21:48
 */

namespace AV\Bundles\Framework\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CacheServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('cache.clearer', 'AV\Cache\CacheClearer')
            ->setArguments(['%cache_dir%'])
        ;
    }
}