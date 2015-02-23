<?php
/**
 * User: Andy
 * Date: 23/02/15
 * Time: 13:14
 */

namespace AVCMS\Bundles\PrivateMessages\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PrivateMessageServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('private_messages.subscriber', 'AVCMS\Bundles\PrivateMessages\EventSubscriber\PrivateMessagesSubscriber')
            ->setArguments([new Reference('router')])
            ->addTag('event.subscriber')
        ;
    }
}
