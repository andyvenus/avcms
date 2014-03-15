<?php
/**
 * User: Andy
 * Date: 15/12/2013
 * Time: 13:15
 */

namespace AVCMS\Bundles\UsersBase;

use AVCMS\Core\Model\ModelFactory;
use AVCMS\Bundles\UsersBase\Exception\IncorrectLoginException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ActiveUser implements EventSubscriberInterface {

    protected $user;

    /**
     * @var \AVCMS\Core\Model\Model|\AVCMS\Bundles\UsersBase\Model\Users
     */
    protected $users_model;

    /**
     * @var \AVCMS\Core\Model\Model
     */
    protected $permissions_model;


    protected $session;

    protected $request;

    /**
     * @var bool
     */
    protected $user_logged_in = false;

    public function __construct(ModelFactory $model_factory, $users_model, $sessions_model, $groups_model = null, $permissions_model = null)
    {
        $this->users_model = $model_factory->create($users_model);
        $this->sessions_model = $model_factory->create($sessions_model);

        if ($permissions_model) {
            $this->permissions_model = $model_factory->create($permissions_model);
        }
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        if (isset($this->user)) {
            return $this->user;
        }
    }

    /**
     * Get the request from the kernel request event
     *
     * @param GetResponseEvent $event
     */
    public function kernelRequestEvent(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $this->getActiveUserFromRequest($event->getRequest());
    }

    /**
     * Look for the user credentials and authorize them
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function getActiveUserFromRequest(Request $request)
    {
        $this->request = $request;

        $user_id = $this->request->cookies->get('avcms_user_id', null);
        $session_id = $this->request->cookies->get('avcms_session', null);

        $session = $this->sessions_model->getSession($session_id, $user_id);

        if ($session !== null && $session->getSessionId() === $session_id) {
            $this->user_logged_in = true;
            $this->user = $this->users_model->getOne($user_id);
        }

        if (!isset($this->user) || !$this->user) {
            $this->user_logged_in = false;
            $this->user = $this->getUnregisteredUser($this->request);
        }
    }

    public function loggedIn()
    {
        return $this->user_logged_in;
    }

    public function loggedInSecure()
    {
        return false;
    }

    public function logIn($identifier, $password)
    {
        $login_handler = new LoginHandler($this->users_model, $this->sessions_model);
        $login_handler->logIn($identifier, $password);

        if ($login_handler->loginSuccess()) {
            $this->user_logged_in = true;
        }

        return $login_handler;
    }

    public function setSessionCookie(Response $response)
    {
        $response->headers->setCookie(new Cookie('avcms_session', $this->session->getId()));
        $response->headers->setCookie(new Cookie('avcms_user_id', $this->session->getUserId()));
    }

    protected function getUnregisteredUser(Request $request)
    {
        $user_entity = $this->users_model->getEntity();
        $user = new $user_entity();
        $user->setUsername('Unregistered');

        return $user;
    }


    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'kernelRequestEvent',
        );
    }
}