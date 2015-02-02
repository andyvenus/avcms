<?php
/**
 * User: Andy
 * Date: 02/02/15
 * Time: 14:54
 */

namespace AVCMS\Core\Security\AuthProvider;

use AVCMS\Bundles\Users\Model\User;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class BCDaoAuthenticationProvider extends DaoAuthenticationProvider
{
    private $encoderFactory;
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider, UserCheckerInterface $userChecker, $providerKey, EncoderFactoryInterface $encoderFactory, $hideUserNotFoundExceptions = true)
    {
        parent::__construct($userProvider, $userChecker, $providerKey, $encoderFactory, $hideUserNotFoundExceptions);

        $this->encoderFactory = $encoderFactory;
        $this->userProvider = $userProvider;
    }

    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        // legacy support for sad old md5 passwords
        if ($user instanceof User && strlen($user->getPassword()) === 32 && md5($token->getCredentials()) === $user->getPassword()) {
            $newPassword = $this->encoderFactory->getEncoder($user)->encodePassword($token->getCredentials(), null);
            $user->setPassword($newPassword);
            $this->userProvider->save($user);
        }

        parent::checkAuthentication($user, $token);
    }
}
