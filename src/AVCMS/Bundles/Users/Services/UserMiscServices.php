<?php
/**
 * User: Andy
 * Date: 14/11/14
 * Time: 10:39
 */

namespace AVCMS\Bundles\Users\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class UserMiscServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('permissions.choices_provider', 'AVCMS\Bundles\Users\Form\ChoicesProvider\PermissionsChoicesProvider')
            ->setArguments([new Reference('users.permissions_model')])
        ;

        $container->register('users.activity_subscriber', 'AVCMS\Bundles\Users\Subscriber\UserActivitySubscriber')
            ->setArguments([new Reference('security.token_storage'), new Reference('users.model')])
            ->addTag('event.subscriber')
        ;

        $container->register('users.groups_choices_provider', 'AVCMS\Bundles\Users\Form\ChoicesProvider\UserGroupChoicesProvider')
            ->setArguments([new Reference('users.groups_model')])
        ;
    }
}
