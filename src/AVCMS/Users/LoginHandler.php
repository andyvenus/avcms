<?php
/**
 * User: Andy
 * Date: 11/02/2014
 * Time: 10:39
 */

namespace AVCMS\Users;

use AVCMS\Core\Form\FormError;
use AVCMS\Users\Model\Sessions;
use AVCMS\Users\Model\Users;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginHandler
{
    /**
     * @var bool
     */
    protected $login_success = false;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \AVCMS\Users\Model\Users
     */
    protected $users_model;

    /**
     * @var \AVCMS\Users\Model\Sessions
     */
    protected $sessions_model;

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var \AVCMS\Users\Model\Session
     */
    protected $session;

    /**
     * @var \AVCMS\Users\Model\User
     */
    protected $unauthorized_user;

    /**
     * @param Request $request
     * @param Users $users_model
     * @param Sessions $sessions_model
     */
    public function __construct(Request $request, Users $users_model, Sessions $sessions_model)
    {
        $this->request = $request;
        $this->users_model = $users_model;
        $this->sessions_model = $sessions_model;
    }

    /**
     * Validates a username/email & password combo and logs the user in
     *
     * @param $identifier string The username of email address of the user
     * @param $password string The raw user password
     * @return bool
     */
    public function logIn($identifier, $password)
    {
        unset($this->errors);
        $this->login_success = false;

        $this->unauthorized_user = $this->users_model->getByUsernameOrEmail($identifier);

        if (!$this->unauthorized_user) {
            $this->errors[] = new FormError('identifier', "Cannot find an account that has that username or email address", true);
            return false;
        }

        if (!password_verify($password, $this->unauthorized_user->getPassword())) {
            $this->errors[] = new FormError('password', "The password you entered is incorrect", true);
            return false;
        }

        if (!isset($this->errors)) {
            $this->session = $this->sessions_model->generateAndSaveSession($this->unauthorized_user->getId());
            $this->login_success = true;
        }

        return $this->login_success;
    }

    /**
     * Returns whether the login was successful or not
     *
     * @return bool
     */
    public function loginSuccess()
    {
        return $this->login_success;
    }

    /**
     * Returns an array of any errors generated during the login
     *
     * @return array|null
     */
    public function getErrors()
    {
        return (isset($this->errors) ? $this->errors : null);
    }

    /**
     * Assigns the session cookies to the passed-in response
     *
     * @param Response $response
     * @throws \Exception
     */
    public function getLoginCookies(Response $response)
    {
        if (!isset($this->session)) {
            throw new \Exception("Cannot get login cookies, the user was not logged in successfully");
        }

        $response->headers->setCookie(new Cookie('avcms_session', $this->session->getSessionId()));
        $response->headers->setCookie(new Cookie('avcms_user_id', $this->session->getUserId()));
    }
}