<?php
/**
 * User: Andy
 * Date: 29/10/14
 * Time: 14:29
 */

namespace AVCMS\Bundles\Users\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class UserTwigServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('twig.security_extension', 'Symfony\Bridge\Twig\Extension\SecurityExtension')
            ->setArguments(array(new Reference('security.auth_checker')))
            ->addTag('twig.extension')
        ;

        $container->register('twig.user_avatar_extension', 'AVCMS\Bundles\Users\TwigExtension\UserAvatarTwigExtension')
            ->setArguments(['%avatar_path%', new Reference('request.stack')])
            ->addTag('twig.extension')
        ;

        $container->register('twig.user_info_extension', 'AVCMS\Bundles\Users\TwigExtension\UserInfoTwigExtension')
            ->setArguments([new Reference('security.token_storage')])
            ->addTag('twig.extension')
        ;
    }
}
