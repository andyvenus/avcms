<?php
/**
 * User: Andy
 * Date: 15/04/15
 * Time: 14:08
 */

namespace AVCMS\Bundles\Demo\EventSubscriber;

use AV\Form\Event\FormHandlerConstructEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AdminFieldRemover implements EventSubscriberInterface
{
    private $adminRequestMatcher;

    private $doRemove = false;

    public function __construct(RequestMatcher $adminRequestMatcher)
    {
        $this->adminRequestMatcher = $adminRequestMatcher;
    }

    public function checkRequest(GetResponseEvent $event)
    {
        if ($this->adminRequestMatcher->matches($event->getRequest())) {
            $this->doRemove = true;
        }
    }

    public function removeFields(FormHandlerConstructEvent $event)
    {
        if (!$this->doRemove) {
            return;
        }

        $blueprint = $event->getFormBlueprint();

        $blueprint->remove('email');
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['checkRequest'],
            'form_handler.construct' => ['removeFields']
        ];
    }
}
