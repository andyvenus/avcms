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
    }
}