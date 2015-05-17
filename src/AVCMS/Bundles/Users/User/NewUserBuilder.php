<?php
/**
 * User: Andy
 * Date: 13/01/15
 * Time: 11:55
 */

namespace AVCMS\Bundles\Users\User;

use AVCMS\Bundles\Users\Event\CreateUserEvent;
use AVCMS\Bundles\Users\Model\Users;
use Cocur\Slugify\Slugify;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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

    private $eventDispatcher;

    public function __construct(Users $users, Slugify $slugify, PasswordEncoderInterface $passwordEncoder, RequestStack $requestStack, EventDispatcherInterface $eventDispatcher)
    {
        $this->users = $users;
        $this->slugify = $slugify;
        $this->passwordEncoder = $passwordEncoder;
        $this->requestStack = $requestStack;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createNewUser($username, $email, $password = null, $role = 'ROLE_USER')
    {
        $user = $this->users->newEntity();

        $user->setUsername($username);
        $user->setEmail($email);

        $slug = $this->slugify->slugify($username);

        if ($this->users->query()->where('slug', $slug)->count()) {
            $slug .= time();
        }

        $user->setSlug($slug);

        if ($password !== null) {
            $encodedPassword = $this->passwordEncoder->encodePassword($password, null);
            $user->setPassword($encodedPassword);
        }

        $user->setRoleList($role);

        $ip = $this->requestStack->getCurrentRequest()->getClientIp();
        $user->setRegistrationIp($ip);
        $user->setLastIp($ip);

        $user->setJoined(time());

        $event = new CreateUserEvent($user);
        $this->eventDispatcher->dispatch('user.create', $event);

        return $user;
    }
}
