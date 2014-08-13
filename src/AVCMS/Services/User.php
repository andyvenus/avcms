<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:38
 */

namespace AVCMS\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class User implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('active.user', 'AVCMS\Bundles\Users\ActiveUser')
            ->setArguments(array(
                new Reference('model_factory'),
                'AVCMS\Bundles\Users\Model\Users',
                'AVCMS\Bundles\Users\Model\Sessions',
                'AVCMS\Bundles\Users\Model\Groups',
                'AVCMS\Bundles\Users\Model\GroupPermissions'
            ))
            ->addTag('event.subscriber')
        ;
    }
}