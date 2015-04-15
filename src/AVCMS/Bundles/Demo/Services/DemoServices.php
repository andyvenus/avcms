<?php
/**
 * User: Andy
 * Date: 15/04/15
 * Time: 13:33
 */

namespace AVCMS\Bundles\Demo\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DemoServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('demo.post_blocker.subscriber', 'AVCMS\Bundles\Demo\EventSubscriber\AdminPostBlocker')
            ->setArguments([new Reference('admin.request_matcher')])
            ->addTag('event.subscriber')
        ;

        $container->register('demo.field_remover.subscriber', 'AVCMS\Bundles\Demo\EventSubscriber\AdminFieldRemover')
            ->setArguments([new Reference('admin.request_matcher')])
            ->addTag('event.subscriber')
        ;

        $container->register('demo.block_page.subscriber', 'AVCMS\Bundles\Demo\EventSubscriber\BlockPage')
            ->addTag('event.subscriber')
        ;
    }
}
