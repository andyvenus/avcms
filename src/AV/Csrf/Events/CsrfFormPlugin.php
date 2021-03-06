<?php
/**
 * User: Andy
 * Date: 21/07/2014
 * Time: 14:03
 */

namespace AV\Csrf\Events;

use AV\Csrf\CsrfToken;
use AV\Form\Event\FormHandlerConstructEvent;
use AV\Form\Event\FormHandlerRequestEvent;
use AV\Form\FormError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CsrfFormPlugin
 * @package AV\Csrf\Events
 *
 * Adds a hidden CSRF prevention field to all forms
 */
class CsrfFormPlugin implements EventSubscriberInterface
{
    /**
     * @var \AV\Csrf\CsrfToken
     */
    protected $token;

    public function __construct(CsrfToken $csrfToken)
    {
        $this->token = $csrfToken;
    }

    public function addTokenField(FormHandlerConstructEvent $event)
    {
        $formBlueprint = $event->getFormBlueprint();

        if ($formBlueprint->getMethod() !== 'GET') {
            $formBlueprint->add('_csrf_token', 'hidden', array(
                'default' => $this->token->getToken()
            ));
        }
    }

    public function validateToken(FormHandlerRequestEvent $event)
    {
        if (!$event->getFormHandler()->isSubmitted()) {
            return;
        }

        if (!$event->getFormHandler()->getFormBlueprint()->has('_csrf_token')) {
            return;
        }

        $token = $event->getFormData()['_csrf_token'];

        if ($this->token->checkToken($token) === false) {
            $formHandler = $event->getFormHandler();
            $formHandler->addCustomErrors(array(new FormError('_csrf_token', 'Invalid CSRF token. Try refreshing the page and trying again.')));
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'form_handler.construct' => array('addTokenField'),
            'form_handler.request' => array('validateToken')
        );
    }
}
