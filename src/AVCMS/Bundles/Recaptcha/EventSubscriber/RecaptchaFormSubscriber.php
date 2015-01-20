<?php
/**
 * User: Andy
 * Date: 20/01/15
 * Time: 14:03
 */

namespace AVCMS\Bundles\Recaptcha\EventSubscriber;

use AV\Form\Event\FilterNewFormEvent;
use AV\Form\Event\FormHandlerRequestEvent;
use AV\Form\FormError;
use AVCMS\Core\SettingsManager\SettingsManager;
use Curl\Curl;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecaptchaFormSubscriber implements EventSubscriberInterface
{
    protected $settingsManager;

    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
        $this->enabled = $this->settingsManager->getSetting('recaptcha_enabled') === '1' ? true : false;
    }

    public function addRecaptchaField(FilterNewFormEvent $event)
    {
        $blueprint = $event->getFormBlueprint();

        if (!$blueprint->has('recaptcha')) {
            return;
        }

        if ($this->enabled === false) {
            $blueprint->remove('recaptcha');
            return;
        }

        $blueprint->replace('recaptcha', 'text', [
            'field_template' => '@Recaptcha/recaptcha_field.twig',
            'allow_unset' => true
        ]);
    }

    public function validateField(FormHandlerRequestEvent $event)
    {
        $formHandler = $event->getFormHandler();

        if ($formHandler->isSubmitted() && $formHandler->hasFieldWithName('recaptcha')) {
            $request = $event->getRequest();

            $response = urlencode($request->get('g-recaptcha-response'));

            $curl = new Curl();

            $secretKey = $this->settingsManager->getSetting('recaptcha_secret');

            $response = $curl->get('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$response);

            if (!isset($response->success)) {
                $formHandler->addCustomErrors([new FormError('recaptcha', 'Something went wrong verifying the captcha')]);
            }

            if ($response->success !== true) {
                $formHandler->addCustomErrors([new FormError('recaptcha', 'Please confirm you are not a robot')]);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'form_factory.create' => ['addRecaptchaField'],
            'form_handler.request' => ['validateField']
        ];
    }
}
