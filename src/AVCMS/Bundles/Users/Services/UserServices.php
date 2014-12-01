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
use Symfony\Component\HttpKernel\KernelEvents;

class UserServices implements ServicesInterface
{
    public function getServices($configuration, ContainerBuilder $container)
    {
        $container->register('users.model', 'AVCMS\Bundles\Users\Model\Users')
            ->setArguments(array('AVCMS\Bundles\Users\Model\Users'))
            ->setFactory([new Reference('model_factory'), 'create'])
            ->addMethodCall('setGroupsModel', [new Reference('users.groups_model')])
        ;

        $container->register('users.sessions_model', 'AVCMS\Bundles\Users\Model\Sessions')
            ->setArguments(array('AVCMS\Bundles\Users\Model\Sessions'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('users.groups_model', 'AVCMS\Bundles\Users\Model\UserGroups')
            ->setArguments(array('AVCMS\Bundles\Users\Model\UserGroups'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('users.permissions_model', 'AVCMS\Bundles\Users\Model\Permissions')
            ->setArguments(array('AVCMS\Bundles\Users\Model\Permissions'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

        $container->register('users.group_permissions_model', 'AVCMS\Bundles\Users\Model\GroupPermissions')
            ->setArguments(array('AVCMS\Bundles\Users\Model\GroupPermissions'))
            ->setFactory([new Reference('model_factory'), 'create'])
        ;

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
                'Symfony\Component\Security\Core\User\UserInterface' => new Reference('users.bcrypt_encoder')
            )))
        ;

        $container->register('users.bcrypt_encoder', 'Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder')
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
                'SOME_KEY',
                'persistent.remember',
                array('name' => 'avcms_remember_me',
                    'remember_me_parameter' => 'remember',
                    'path' => '/',
                    'domain' => null,
                    'always_remember_me' => false,
                    'lifetime' => 99999,
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

        // ACCESS LISTENER

        $container->register('auth.access_listener', 'Symfony\Component\Security\Http\Firewall\AccessListener')
            ->setArguments([new Reference('security.context'), new Reference('auth.access_decision_manager'), new Reference('auth.access_map'), new Reference('users.auth_manager')])
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handle', 'priority' => -101])
        ;

        $container->register('auth.access_map', 'Symfony\Component\Security\Http\AccessMap')
            ->addMethodCall('add', [new Reference('auth.admin_request_matcher'), ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']])
        ;

        $container->register('auth.admin_request_matcher', 'Symfony\Component\HttpFoundation\RequestMatcher')
            ->setArguments(['^/admin'])
        ;

        $container->register('auth.access_listener.fully', 'Symfony\Component\Security\Http\Firewall\AccessListener')
            ->setArguments([new Reference('security.context'), new Reference('auth.access_decision_manager'), new Reference('auth.access_map.fully'), new Reference('users.auth_manager')])
            ->addTag('event.listener', ['event' => KernelEvents::REQUEST, 'method' => 'handle', 'priority' => -101])
        ;

        $container->register('auth.access_map.fully', 'Symfony\Component\Security\Http\AccessMap')
            ->addMethodCall('add', [new Reference('auth.admin_request_matcher'), ['IS_AUTHENTICATED_FULLY']])
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

        // EXCEPTION LISTENER
        $container->register('auth.exception_listener', 'Symfony\Component\Security\Http\Firewall\ExceptionListener')
            ->setArguments([new Reference('security.context'), new Reference('auth.trust_resolver'), new Reference('http.utils'), 'username.password', new Reference('auth.form_entry_point'), 'home'])
            ->addTag('event.listener', ['event' => KernelEvents::EXCEPTION, 'method' => 'onKernelException'])
        ;

        $container->register('auth.form_entry_point', 'Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint')
            ->setArguments([new Reference('http_kernel'), new Reference('http.utils'), '/login'])
        ;

        // UPDATE PERMISSION SUBSCRIBER
        $container->register('subscriber.user.update_permissions', 'AVCMS\Bundles\Users\Subscriber\UpdateBundlePermissionsSubscriber')
            ->setArguments([new Reference('bundle_manager'), new Reference('users.permissions_model')])
            ->addTag('event.subscriber')
        ;
    }
}