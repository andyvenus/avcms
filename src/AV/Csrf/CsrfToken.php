<?php
/**
 * User: Andy
 * Date: 21/07/2014
 * Time: 13:51
 */

namespace AV\Csrf;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class CsrfToken
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var null|string
     */
    protected $token = null;

    public function __construct($prefix = 'av_')
    {
        $this->prefix = $prefix;
    }

    public function checkToken($token)
    {
        if ($token === $this->token) {
            return true;
        }

        return false;
    }

    public function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(40));
    }

    public function getToken()
    {
        return $this->token;
    }

    public function handleRequest(GetResponseEvent $event)
    {
        $token = $event->getRequest()->cookies->get($this->prefix.'csrf_token', null);

        if ($token === null) {
            $token = $this->generateToken();
        }

        $this->token = $token;
    }

    public function handleResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->setCookie(new Cookie($this->prefix.'csrf_token', $this->token, 0, '/', null, false, false));
    }
}