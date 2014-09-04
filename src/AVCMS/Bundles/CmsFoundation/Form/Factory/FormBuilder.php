<?php
/**
 * User: Andy
 * Date: 03/09/2014
 * Time: 16:02
 */

namespace AVCMS\Bundles\CmsFoundation\Form\Factory;

use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Form\FormHandlerFactory;
use AVCMS\Core\Form\FormView;
use AVCMS\Core\Form\ValidatorExtension\AVCMSValidatorExtension;
use AVCMS\Core\Validation\Validator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FormBuilder
{
    public function __construct(FormHandlerFactory $form_handler_factory, TranslatorInterface $translator, EventDispatcherInterface $event_dispatcher)
    {
        $this->form_handler_factory = $form_handler_factory;
        $this->translator = $translator;
        $this->event_dispatcher = $event_dispatcher;
    }

    public function buildForm(FormBlueprint $form, $request = null, $entities = array(), $form_view = null)
    {
        if (!$form_view) {
            $form_view = new FormView();
            $form_view->setTranslator($this->translator);
        }

        $validator = new Validator();
        $validator->setTranslator($this->translator);
        $validator->setEventDispatcher($this->event_dispatcher);
        $validator_ex = new AVCMSValidatorExtension($validator);

        $form_handler = $this->form_handler_factory->buildForm($form, $form_view, $validator_ex);

        if (!is_array($entities)) {
            $entities = array($entities);
        }
        foreach ($entities as $entity) {
            $form_handler->bindEntity($entity);
        }

        if ($request) {
            $form_handler->handleRequest($request);
        }

        return $form_handler;
    }
} 