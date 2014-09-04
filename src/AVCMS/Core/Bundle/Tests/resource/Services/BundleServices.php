<?php
namespace AVCMS\Core\Bundle\Tests\Resource\Services;

use AVCMS\Core\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * User: Andy
 * Date: 20/08/2014
 * Time: 16:29
 */

class BundleServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('test.service', 'My\Test\Service');
    }
}