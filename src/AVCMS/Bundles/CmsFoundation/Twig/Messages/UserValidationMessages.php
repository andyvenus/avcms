<?php
/**
 * User: Andy
 * Date: 06/05/15
 * Time: 13:21
 */

namespace AVCMS\Bundles\CmsFoundation\Twig\Messages;

use AVCMS\Bundles\CmsFoundation\Twig\MessagesProviderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserValidationMessages implements MessagesProviderInterface
{
    private $tokenStorage;

    private $translator;

    private $urlGenerator;

    public function __construct(TokenStorageInterface $tokenStorage, TranslatorInterface $translator, UrlGeneratorInterface $urlGenerator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function getMessages()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user->getRoleList() == 'ROLE_NOT_VALIDATED') {
            return [[
                'type' => 'info',
                'message' => $this->translator->trans('You still need to validate your email address ({email}) to get full access', ['email' => $user->getEmail()]),
                'link' => [
                    'url' => $this->urlGenerator->generate('resend_validate_email'),
                    'anchor' => $this->translator->trans('Resend')
                ]
            ]];
        }

        return null;
    }
}
