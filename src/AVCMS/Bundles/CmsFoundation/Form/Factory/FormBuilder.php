<?php
/**
 * User: Andy
 * Date: 03/09/2014
 * Time: 16:02
 */

namespace AVCMS\Bundles\CmsFoundation\Form\Factory;

use AV\Form\FormBlueprint;
use AV\Form\FormHandlerFactory;
use AV\Form\FormView;
use AV\Form\ValidatorExtension\AVValidatorExtension;
use AV\Validation\Validator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FormBuilder implements ContainerAwareInterface
{
    protected $container;

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
        $validator_ex = new AVValidatorExtension($validator);

        if (isset($this->container)) {
            $validator_ex->setContainer($this->container);
        }

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

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}