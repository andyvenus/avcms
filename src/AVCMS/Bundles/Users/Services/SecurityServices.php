<?php
/**
 * User: Andy
 * Date: 05/12/14
 * Time: 14:30
 */

namespace AVCMS\Bundles\Users\Services;

use AV\Service\ServicesInterface;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class SecurityServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        // security.context DEPRECIATED
        $container->register('security.context', 'Symfony\Component\Security\Core\SecurityContext')
            ->setArguments(array(new Reference('security.token_storage'), new Reference('security.auth_checker')))
        ;

        $container->register('security.token_storage', 'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage');

        $container->register('security.auth_checker', 'Symfony\Component\Security\Core\Authorization\AuthorizationChecker')
            ->setArguments([new Reference('security.token_storage'), new Reference('users.auth_manager'), new Reference('auth.access_decision_manager')])
        ;


        $container->register('users.dao_auth_provider', 'Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider')
            ->setArguments(array(new Reference('users.model'), new Reference('users.user_checker'), 'username.password', new Reference('users.encoder_factory'), false))
        ;

        $container->register('users.encoder_factory', 'Symfony\Component\Security\Core\Encoder\EncoderFactory')
            ->setArguments(array(array(
                'Symfony\Component\Security\Core\User\UserInterface' => new Reference('security.bcrypt_encoder')
            )))
        ;

        $container->register('security.bcrypt_encoder', 'Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder')
            ->setArguments(array(9))
        ;

        $container->register('users.user_checker', 'Symfony\Component\Security\Core\User\UserChecker');


        $container->register('users.auth_manager', 'Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager')
            ->setArguments(array(array(new Reference('users.dao_auth_provider'), new Reference('users.remember_me_auth_provider'))))
        ;

        $container->register('auth.context_listener', 'Symfony\Component\Security\Http\Firewall\ContextListener')
            ->setArguments(array(new Reference('security.context'), array(new Reference('users.model')), 'user.context'))
            ->addTag('event.listener', array('event' => KernelEvents::REQUEST, 'method' => 'handle'))
            ->addTag('event.listener', array('event' => KernelEvents::RESPONSE, 'method' => 'onKernelResponse'))
        ;

        // LOGIN
        $container->register('subscriber.login_authentication', 'AVCMS\Core\Security\Subscriber\LoginAuthenticationSubscriber')
            ->setArguments(array(new Reference('security.context'), new Reference('users.auth_manager'), new Reference('auth.session_strategy'), new Reference('http.utils'), 'username.password', new Reference('auth.login_success_handler'), new Reference('auth.login_failure_handler')))
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

        $container->register('listener.auth.remember_me', 'Symfony\Component\Security\Http\Firewall\RememberMeListener')
            ->setArguments(array(new Reference('security.context'), new Reference('auth.remember_me_services'), new Reference('users.auth_manager')))
            ->addTag('event.listener', array('event' => KernelEvents::REQUEST, 'method' => 'handle'))
        ;

        $container->register('subscriber.auth.remember_me_response', 'Symfony\Component\Security\Http\RememberMe\ResponseListener')
            ->addTag('event.subscriber')
        ;

        $container->register('users.remember_me_auth_provider', 'Symfony\Component\Security\Core\Authentication\Provider\RememberMeAuthenticationProvider')
            ->setArguments(array(new Reference('users.user_checker'), 'SOME_KEY', 'persistent.remember'))
        ;

        $container->register('security.random', 'Symfony\Component\Security\Core\Util\SecureRandom');

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
        $container->register('subscriber.anonymous_authentication', 'AVCMS\Core\Security\Subscriber\AnonymousAuthenticationSubscriber')
            ->setArguments(array(new Reference('security.token_storage'), 'anonymous', new Reference('users.groups_model')))
            ->addTag('event.subscriber')
        ;

        // AUTHORIZATION
        $container->register('auth.access_decision_manager', 'Symfony\Component\Security\Core\Authorization\AccessDecisionManager')
            ->setArguments(array(array(new Reference('auth.voter.authenticated'), new Reference('auth.voter.permissions'), new Reference('auth.voter.role'))))
        ;

        $container->register('auth.voter.permissions', 'AVCMS\Core\Security\Voter\PermissionsVoter')
            ->setArguments(array(new Reference('users.group_permissions_model')))
        ;

        $container->register('auth.voter.authenticated', 'Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter')
            ->setArguments(array(new Reference('auth.trust_resolver')))
        ;

        $container->register('auth.voter.role', 'AVCMS\Core\Security\Voter\RoleVoter');

        $container->register('auth.trust_resolver', 'Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver')
            ->setArguments(array('Symfony\Component\Security\Core\Authentication\Token\AnonymousToken', 'Symfony\Component\Security\Core\Authentication\Token\RememberMeToken'))
        ;

        // LOG-OUT
        $container->register('auth.logout_listener', 'Symfony\Component\Security\Http\Firewall\LogoutListener')
            ->setArguments([new Reference('security.context'), new Reference('http.utils'), new Reference('auth.logout_success_handler')])
            ->addMethodCall('addHandler', [new Reference('auth.remember_me_services')])
            ->addMethodCall('addHandler', [new Reference('auth.session_logout_handler')])
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handle'])
        ;

        $container->register('auth.logout_success_handler', 'Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler')
            ->setArguments([new Reference('http.utils')])
        ;

        $container->register('auth.session_logout_handler', 'Symfony\Component\Security\Http\Logout\SessionLogoutHandler');

        // LOGGER
        $container->register('logger', 'Monolog\Logger')
            ->setArguments(['security.logger'])
            ->addMethodCall('pushHandler', [new Reference('logger.stream_handler')])
        ;

        $container->register('logger.stream_handler', 'Monolog\Handler\StreamHandler')
            ->setArguments(['%cache_dir%/logs/security.log', Logger::DEBUG])
        ;

        // SECURITY EXCEPTION LISTENER
        $container->register('auth.exception_listener', 'AVCMS\Core\Security\Firewall\ExceptionListener')
            ->setArguments([new Reference('security.context'), new Reference('auth.trust_resolver'), new Reference('http.utils'), 'username.password', new Reference('auth.form_entry_point'), 'home', new Reference('auth.access_denied_handler'), new Reference('logger')])
            ->addTag('event.listener', ['event' => KernelEvents::EXCEPTION, 'method' => 'onKernelException', 'priority' => -10])
        ;

        $container->register('auth.access_denied_handler', 'AVCMS\Core\Security\Firewall\AccessDeniedHandler')
            ->setArguments([new Reference('twig'), '%default_access_denied_template%', '%access_denied_templates%'])
        ;

        $container->register('auth.form_entry_point', 'Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint')
            ->setArguments([new Reference('http_kernel'), new Reference('http.utils'), '/login?reauth=true'])
        ;
    }
}