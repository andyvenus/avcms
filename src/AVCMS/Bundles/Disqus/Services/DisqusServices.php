<?php
/**
 * User: Andy
 * Date: 24/01/15
 * Time: 16:59
 */

namespace AVCMS\Bundles\Disqus\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DisqusServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('subscriber.disqus', 'AVCMS\Bundles\Disqus\EventSubscriber\DisqusEventSubscriber')
            ->setArguments([new Reference('settings_manager'), new Reference('twig')])
            ->addTag('event.subscriber')
        ;
    }
}
