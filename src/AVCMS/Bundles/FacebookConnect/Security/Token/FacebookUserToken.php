<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 13:09
 */

namespace AVCMS\Bundles\FacebookConnect\Security\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class FacebookUserToken extends AbstractToken
{
    private $providerKey;

    protected $accessToken;

    public function __construct($providerKey, $uid, $accessToken, $user = null, array $roles = array())
    {
        parent::__construct($roles);

        if ($user === null) {
            $this->setUser($uid);
        }
        else {
            $this->setUser($user);
        }

        $this->providerKey = $providerKey;
        $this->accessToken = $accessToken;
    }

    public function getCredentials()
    {
        return '';
    }

    public function getProviderKey()
    {
        return $this->providerKey;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function serialize()
    {
        return serialize(array($this->providerKey, $this->accessToken, parent::serialize()));
    }

    public function unserialize($str)
    {
        list($this->providerKey, $this->accessToken, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
