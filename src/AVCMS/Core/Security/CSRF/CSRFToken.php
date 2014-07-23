<?php
/**
 * User: Andy
 * Date: 21/07/2014
 * Time: 13:51
 */

namespace AVCMS\Core\Security\CSRF;

use Symfony\Component\HttpFoundation\Request;

class CsrfToken
{
    protected $prefix = 'avcms_';

    public function checkToken($token, $request = null)
    {
        if (isset($_COOKIE[$this->prefix.'csrf_token']) && $token === $_COOKIE[$this->prefix.'csrf_token']) {
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
        if (isset($_COOKIE[$this->prefix.'csrf_token']) && strlen($_COOKIE[$this->prefix.'csrf_token']) === 80) {
            return $_COOKIE[$this->prefix.'csrf_token'];
        }
        else {
            return $this->setTokenCookie();
        }
    }

    public function setTokenCookie($value = null)
    {
        if ($value == null) {
            $value = $this->generateToken();
        }

        setcookie($this->prefix.'csrf_token', $value, null, '/');

        return $value;
    }
}