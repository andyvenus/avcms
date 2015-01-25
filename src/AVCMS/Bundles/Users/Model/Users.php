<?php
/**
 * User: Andy
 * Date: 09/02/2014
 * Time: 19:19
 */

namespace AVCMS\Bundles\Users\Model;

use AV\Model\Model;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class Users extends Model implements UserProviderInterface
{
    protected $textIdentifierColumn = 'slug';

    protected $finder = 'AVCMS\Bundles\Users\Model\UserFinder';

    protected $groupsModel;

    public function getTable()
    {
        return 'users';
    }

    public function getSingular()
    {
        return 'user';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Users\Model\User';
    }

    public function getByUsernameOrEmail($identifier)
    {
        return $this->query()->where('username', $identifier)->orWhere('email', $identifier)->first();
    }

    public function setGroupsModel(Model $groupsModel)
    {
        $this->groupsModel = $groupsModel;
    }

    public function updateUserActivity($userId, $ip)
    {
        $lastActivity = time();

        $this->query()->where('id', $userId)->update(['last_activity' => $lastActivity, 'last_ip' => $ip]);
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        $user = $this->query()->where('username', $username)->first();

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username not found.', $username));
        }

        if (isset($this->groupsModel)) {
            $user->group = $this->groupsModel->getOne($user->getRoleList());
        }

        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if ($user instanceof AnonymousUser) {
            return $user;
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return ($this->getEntity() === $class);
    }
}
