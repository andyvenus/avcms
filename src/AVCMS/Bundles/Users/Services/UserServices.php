<?php
/**
 * User: Andy
 * Date: 08/08/2014
 * Time: 16:38
 */

namespace AVCMS\Bundles\Users\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class UserServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('users.model', 'AVCMS\Bundles\Users\Model\Users')
            ->addMethodCall('setGroupsModel', [new Reference('users.groups_model')])
            ->addTag('model')
        ;

        $container->register('users.sessions_model', 'AVCMS\Bundles\Users\Model\Sessions')
            ->addTag('model')
        ;

        $container->register('users.groups_model', 'AVCMS\Bundles\Users\Model\UserGroups')
            ->addTag('model')
        ;

        $container->register('users.permissions_model', 'AVCMS\Bundles\Users\Model\Permissions')
            ->addTag('model')
        ;

        $container->register('users.group_permissions_model', 'AVCMS\Bundles\Users\Model\GroupPermissions')
            ->addTag('model')
        ;

        $container->register('subscriber.user.update_permissions', 'AVCMS\Bundles\Users\Subscriber\UpdateBundlePermissionsSubscriber')
            ->setArguments([new Reference('bundle_manager'), new Reference('users.permissions_model')])
            ->addTag('event.subscriber')
        ;

        $container->register('users.new_user_builder', 'AVCMS\Bundles\Users\User\NewUserBuilder')
            ->setArguments([new Reference('users.model'), new Reference('slug.generator'), new Reference('security.bcrypt_encoder'), new Reference('request.stack'), new Reference('dispatcher')])
        ;
    }
}
