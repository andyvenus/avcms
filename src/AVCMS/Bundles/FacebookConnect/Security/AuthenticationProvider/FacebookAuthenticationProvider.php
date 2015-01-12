<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 14:48
 */

namespace AVCMS\Bundles\FacebookConnect\Security\AuthenticationProvider;

use AVCMS\Bundles\FacebookConnect\Security\Exception\FacebookAccountNotFoundException;
use AVCMS\Bundles\FacebookConnect\Security\Token\FacebookUserToken;
use AVCMS\Bundles\Users\Model\AnonymousUser;
use AVCMS\Bundles\Users\Model\UserGroups;
use AVCMS\Bundles\Users\Model\Users;
use Facebook\FacebookSession;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;

class FacebookAuthenticationProvider implements AuthenticationProviderInterface
{
    protected $providerKey;

    protected $users;

    protected $userGroups;

    public function __construct($providerKey, Users $users, UserGroups $userGroups)
    {
        $this->providerKey = $providerKey;
        $this->users = $users;
        $this->userGroups = $userGroups;
    }

    /**
     * @param TokenInterface|FacebookUserToken $token
     * @return TokenInterface|void
     */
    public function authenticate(TokenInterface $token)
    {
        FacebookSession::setDefaultApplication('1535722406709073', '83145ae5b94ce97884a1e50be68f6991');

        $facebookSession = new FacebookSession($token->getAccessToken());

        if ($facebookSession->validate()) {
            $user = $this->users->query()->where('facebook_id', $token->getUser())->first();
            if (!$user) {
                $user = new AnonymousUser();
                $user->setUsername("Unregistered");
                $user->setRoleList('ROLE_UNREGISTERED');
                $user->group = $this->userGroups->getOne('ROLE_UNREGISTERED');
            }

            $token = new FacebookUserToken($this->providerKey, $facebookSession->getUserId(), $facebookSession->getAccessToken(), $user);
            $token->setAuthenticated(true);

            return $token;
        }
        else {
            throw new CredentialsExpiredException('Facebook session expired');
        }
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof FacebookUserToken && $this->providerKey === $token->getProviderKey();
    }
}
