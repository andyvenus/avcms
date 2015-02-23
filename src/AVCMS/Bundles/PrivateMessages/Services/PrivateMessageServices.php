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
        $container->register('private_messages.template.subscriber', 'AVCMS\Bundles\PrivateMessages\EventSubscriber\PrivateMessagesTemplateSubscriber')
            ->setArguments([new Reference('router'), new Reference('translator'), new Reference('security.token_storage'), new Reference('security.auth_checker')])
            ->addTag('event.subscriber')
        ;

        $container->register('private_messages.model.subscriber', 'AVCMS\Bundles\PrivateMessages\EventSubscriber\PrivateMessagesModelSubscriber')
            ->addTag('event.subscriber')
        ;
    }
}
