<?php
/**
 * User: Andy
 * Date: 29/10/14
 * Time: 14:29
 */

namespace AVCMS\Bundles\Users\Services;

use AV\Service\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class UserTwigServices implements Service
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('twig.security_extension', 'Symfony\Bridge\Twig\Extension\SecurityExtension')
            ->setArguments(array(new Reference('security.context')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.user_avatar_extension', 'AVCMS\Bundles\Users\TwigExtension\UserAvatarTwigExtension')
            ->setArguments(['%avatar_path%', new Reference('request.stack')])
            ->addTag('twig.extension')
        ;
    }
}