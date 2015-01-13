<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 11:55
 */

namespace AVCMS\Bundles\Users\User;

use AVCMS\Bundles\Users\Model\Users;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class NewUserBuilder
{
    /**
     * @var Users
     */
    private $users;

    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    private $requestStack;

    public function __construct(Users $users, Slugify $slugify, PasswordEncoderInterface $passwordEncoder, RequestStack $requestStack)
    {
        $this->users = $users;
        $this->slugify = $slugify;
        $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
    }

    public function createNewUser($username, $email, $password = null, $role = 'ROLE_USER')
    {
        $user = $this->users->newEntity();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setSlug($this->slugify->slugify($username));

        if ($password !== null) {
            $encodedPassword = $this->passwordEncoder->encodePassword($password, null);
            $user->setPassword($encodedPassword);
        }

        $user->setRoleList($role);

        $user->setLastIp($this->requestStack->getCurrentRequest()->getClientIp());

        $user->setJoined(time());

        return $user;
    }
}
