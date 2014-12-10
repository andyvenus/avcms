<?php
/**
 * User: Andy
 * Date: 05/12/14
 * Time: 14:30
 */

namespace AVCMS\Bundles\Users\Services;

use AV\Service\ServicesInterface;
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
            ->setArguments([new Reference('security.token_storage'), new Reference('auth.manager'), new Reference('auth.access_decision_manager')])
        ;

        $container->register('security.encoder_factory', 'Symfony\Component\Security\Core\Encoder\EncoderFactory')
            ->setArguments(array(array(
                'Symfony\Component\Security\Core\User\UserInterface' => new Reference('security.bcrypt_encoder')
            )))
        ;

        $container->register('security.bcrypt_encoder', 'Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder')
            ->setArguments(array(9))
        ;

        $container->register('security.random', 'Symfony\Component\Security\Core\Util\SecureRandom');

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

        // SECURITY EXCEPTION LISTENER
        $container->register('auth.exception_listener', 'AVCMS\Core\Security\Firewall\ExceptionListener')
            ->setArguments([new Reference('security.context'), new Reference('auth.trust_resolver'), new Reference('http.utils'), 'username.password', new Reference('auth.form_entry_point'), 'home', new Reference('auth.access_denied_handler')])
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