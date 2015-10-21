<?php
/**
 * User: Andy
 * Date: 20/09/15
 * Time: 14:35
 */

namespace AVCMS\Bundles\FacebookConnect\Facebook;

use Facebook\PersistentData\PersistentDataInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SymfonySession implements PersistentDataInterface
{
    /**
     * @var string Prefix to use for session variables.
     */
    protected $sessionPrefix = 'FBRLH_';

    /**
     * @var Session
     */
    protected $session;

    /**
     * Init the session handler.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->session->get($this->sessionPrefix . $key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->session->set($this->sessionPrefix . $key, $value);
    }
}
