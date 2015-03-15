<?php
/**
 * User: Andy
 * Date: 15/03/15
 * Time: 16:06
 */

namespace AVCMS\Bundles\Friends\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FriendsServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('model.friend_requests', 'AVCMS\Bundles\Friends\Model\FriendRequests')
            ->addTag('model')
        ;

        $container->register('friends.template.subscriber', 'AVCMS\Bundles\Friends\EventSubscriber\FriendsTemplateSubscriber')
            ->setArguments([new Reference('router'), new Reference('translator'), new Reference('security.token_storage'), new Reference('security.auth_checker'), new Reference('model.friend_requests')])
            ->addTag('event.subscriber')
        ;
    }
}
