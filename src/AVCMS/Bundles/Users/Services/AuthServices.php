<?php
/**
 * User: Andy
 * Date: 07/12/14
 * Time: 21:03
 */

namespace AVCMS\Bundles\Users\Services;

use AV\Service\ServicesInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('auth.context_listener', 'Symfony\Component\Security\Http\Firewall\ContextListener')
            ->setArguments(array(new Reference('security.context'), array(new Reference('users.model')), 'user.context'))
            ->addTag('event.listener', array('event' => KernelEvents::REQUEST, 'method' => 'handle'))
            ->addTag('event.listener', array('event' => KernelEvents::RESPONSE, 'method' => 'onKernelResponse'))
        ;

        $container->register('auth.manager', 'Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager')
            ->setArguments(array(array(new Reference('auth.dao_provider'), new Reference('auth.remember_me_provider'), new Reference('facebook_connect.provider'))))
        ;

        $container->register('auth.dao_provider', 'AVCMS\Core\Security\AuthProvider\BCDaoAuthenticationProvider')
            ->setArguments(array(new Reference('users.model'), new Reference('auth.user_checker'), 'username.password', new Reference('security.encoder_factory'), false))
        ;

        $container->register('auth.user_checker', 'Symfony\Component\Security\Core\User\UserChecker');

        $container->register('auth.subscriber.login_authentication', 'AVCMS\Core\Security\Subscriber\LoginAuthenticationSubscriber')
            ->setArguments(array(new Reference('security.context'), new Reference('auth.manager'), new Reference('auth.session_strategy'), new Reference('http.utils'), 'username.password', new Reference('auth.login_success_handler'), new Reference('auth.login_failure_handler')))
            ->addMethodCall('setRememberMeServices', array(new Reference('auth.remember_me_services')))
            ->addTag('event.subscriber')
        ;

        $container->register('auth.remember_me_services', 'Symfony\Component\Security\Http\RememberMe\PersistentTokenBasedRememberMeServices')
            ->setArguments(array(
                array(new Reference('users.model')),
                'remember_me_token',
                'persistent.remember',
                array('name' => 'avcms_remember_me',
                    'remember_me_parameter' => 'remember',
                    'path' => '/',
                    'domain' => null,
                    'always_remember_me' => false,
                    'lifetime' => 1209600,
                    'secure' => false,
                    'httponly' => true
                ),
                null,
                new Reference('security.random')
            ))
            ->addMethodCall('setTokenProvider', array(new Reference('users.sessions_model')))
        ;

        $container->register('auth.listener.remember_me', 'AVCMS\Core\Security\Subscriber\RememberMeListener')
            ->setArguments(array(new Reference('security.context'), new Reference('auth.remember_me_services'), new Reference('auth.manager')))
            ->addTag('event.listener', array('event' => KernelEvents::REQUEST, 'method' => 'handle'))
        ;

        $container->register('auth.subscriber.remember_me_response', 'Symfony\Component\Security\Http\RememberMe\ResponseListener')
            ->addTag('event.subscriber')
        ;

        $container->register('auth.remember_me_provider', 'Symfony\Component\Security\Core\Authentication\Provider\RememberMeAuthenticationProvider')
            ->setArguments(array(new Reference('auth.user_checker'), 'remember_me_token', 'persistent.remember'))
        ;

        $container->register('auth.session_strategy', 'Symfony\Component\Security\Http\Session\SessionAuthenticationStrategy')
            ->setArguments(array('migrate'))
        ;

        $container->register('auth.login_success_handler', 'Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler')
            ->setArguments(array(new Reference('http.utils'), array()))
            ->addMethodCall('setProviderKey', ['username.password'])
        ;

        $container->register('auth.login_failure_handler', 'Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler')
            ->setArguments(array(new Reference('http_kernel'), new Reference('http.utils'), array()))
        ;

        // ANONYMOUS
        $container->register('auth.subscriber.anonymous_authentication', 'AVCMS\Core\Security\Subscriber\AnonymousAuthenticationSubscriber')
            ->setArguments(array(new Reference('security.token_storage'), 'anonymous', new Reference('users.model'), new Reference('users.groups_model')))
            ->addTag('event.subscriber')
        ;

        // LOG-OUT
        $container->register('auth.listener.logout', 'Symfony\Component\Security\Http\Firewall\LogoutListener')
            ->setArguments([new Reference('security.context'), new Reference('http.utils'), new Reference('auth.logout_success_handler')])
            ->addMethodCall('addHandler', [new Reference('auth.remember_me_services')])
            ->addMethodCall('addHandler', [new Reference('auth.session_logout_handler')])
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handle'])
        ;

        $container->register('auth.logout_success_handler', 'Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler')
            ->setArguments([new Reference('http.utils')])
        ;

        $container->register('auth.session_logout_handler', 'Symfony\Component\Security\Http\Logout\SessionLogoutHandler');
    }
}
