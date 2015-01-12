<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 15:08
 */

namespace AVCMS\Bundles\FacebookConnect\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FacebookConnectServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('facebook_connect.listener', 'AVCMS\Bundles\FacebookConnect\Security\AuthenticationListener\FacebookAuthenticationListener')
            ->setArguments(array(new Reference('security.context'), new Reference('auth.manager'), new Reference('auth.session_strategy'), new Reference('http.utils'), 'facebook_connect', new Reference('auth.login_success_handler'), new Reference('auth.login_failure_handler'), ['check_path' => '/facebook-login-check', 'login_path' => '/facebook-login']))
            ->addMethodCall('setRememberMeServices', array(new Reference('auth.remember_me_services')))
            ->addTag('event.subscriber')
        ;

        $container->register('facebook_connect.provider', 'AVCMS\Bundles\FacebookConnect\Security\AuthenticationProvider\FacebookAuthenticationProvider')
            ->setArguments(['facebook_connect', new Reference('users.model'), new Reference('users.groups_model')])
        ;
    }
}
