<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 15:26
 */

namespace AVCMS\Bundles\Users\Model;

use AVCMS\Core\Model\Model;
use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentTokenInterface;
use Symfony\Component\Security\Core\Authentication\RememberMe\TokenProviderInterface;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

class Sessions extends Model implements TokenProviderInterface
{
    public function getTable()
    {
        return 'sessions';
    }

    public function getSingular()
    {
        return 'session';
    }

    public function getEntity()
    {
        return 'AVCMS\Bundles\Users\Model\Session';
    }

    public function generateAndSaveSession($user_id)
    {
        $entity = $this->getEntity();
        $session = new $entity;

        $session->setSessionId(bin2hex(openssl_random_pseudo_bytes(40)));
        $session->setUserId($user_id);
        $session->setGenerated(time());

        $this->insert($session);

        return $session;
    }

    public function getSession($session_id, $user_id)
    {
        return $this->query()->where('session_id', $session_id)->where('user_id', $user_id)->first();
    }

    /**
     * Loads the active token for the given series.
     *
     * @param string $series
     *
     * @return PersistentTokenInterface
     *
     * @throws TokenNotFoundException if the token is not found
     */
    public function loadTokenBySeries($series)
    {
        $token = $this->query()->where('series', $series)->first();

        if (!$token) {
            throw new TokenNotFoundException;
        }

        return $token;
    }

    /**
     * Deletes all tokens belonging to series.
     *
     * @param string $series
     */
    public function deleteTokenBySeries($series)
    {
        $this->query()->where('series', $series)->delete();
    }

    /**
     * Updates the token according to this data.
     *
     * @param string $series
     * @param string $tokenValue
     * @param \DateTime $lastUsed
     * @throws TokenNotFoundException if the token is not found
     */
    public function updateToken($series, $tokenValue, \DateTime $lastUsed)
    {
        $session = new Session();
        $session->setSeries($series);
        $session->setTokenValue($tokenValue);
        $session->setLastUsed($lastUsed);

        $this->query()->where('series', $series)->update($session);
    }

    /**
     * Creates a new token.
     *
     * @param PersistentTokenInterface $token
     */
    public function createNewToken(PersistentTokenInterface $token)
    {
        $paramValues = array(
            'class' => $token->getClass(),
            'username' => $token->getUsername(),
            'series'   => $token->getSeries(),
            'token_value'    => $token->getTokenValue(),
            'lastUsed' => $token->getLastUsed()
        );
        $session = new Session();
        $session->fromArray($paramValues);

        $this->insert($session);
    }
}