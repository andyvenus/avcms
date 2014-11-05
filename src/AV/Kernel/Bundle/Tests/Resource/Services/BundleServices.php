<?php
namespace AV\Kernel\Bundle\Tests\Resource\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * User: Andy
 * Date: 20/08/2014
 * Time: 16:29
 */

class BundleServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('test.service', 'My\Test\Service');
    }
}