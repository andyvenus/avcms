<?php
/**
 * User: Andy
 * Date: 15/12/2013
 * Time: 13:15
 */

namespace AVCMS\User;

use AVCMS\Model\ModelFactory;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ActiveUser implements EventSubscriberInterface {

    protected $container;

    protected $user;

    protected $users_model;

    public function __construct(Container $container, ModelFactory $model_factory)
    {
        $this->container = $container;
        $this->users_model = $model_factory->create('Games\Model\Games');
    }

    public function getUser()
    {
        if (isset($this->user)) {
            return $this->user;
        }
    }

    public function userLoggedIn()
    {
        return isset($this->user);
    }

    /**
     * Look for the user credentials and authorize them
     *
     * @param GetResponseEvent $event
     */
    public function onKernelResponse(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();

        $user_id = $request->cookies->get('avcms_userid');

        $this->user = $this->users_model->getOne($user_id);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelResponse',
        );
    }
}