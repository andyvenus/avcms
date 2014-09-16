<?php
/**
 * User: Andy
 * Date: 21/07/2014
 * Time: 14:03
 */

namespace AVCMS\Core\Security\Csrf\Events;

use AVCMS\Core\Form\Event\FormHandlerConstructEvent;
use AVCMS\Core\Form\Event\FormHandlerRequestEvent;
use AVCMS\Core\Form\FormError;
use AVCMS\Core\Security\CSRF\CsrfToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CsrfFormPlugin implements EventSubscriberInterface
{
    /**
     * @var \AVCMS\Core\Security\CSRF\CsrfToken
     */
    protected $token;

    public function __construct(CsrfToken $csrfToken)
    {
        $this->token = $csrfToken;
    }

    public function addTokenField(FormHandlerConstructEvent $event)
    {
        $formBlueprint = $event->getFormBlueprint();

        $formBlueprint->add('_csrf_token', 'hidden', array(
            'default' => $this->token->getToken()
        ));
    }

    public function validateToken(FormHandlerRequestEvent $event)
    {
        $token = $event->getFormData()['_csrf_token'];

        if ($this->token->checkToken($token) === false) {
            $formHandler = $event->getFormHandler();
            $formHandler->addCustomErrors(array(new FormError('_csrf_token', 'Invalid CSRF token')));
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