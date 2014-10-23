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

    protected $formHandlerFactory;

    protected $translator;

    protected $eventDispatcher;

    public function __construct(FormHandlerFactory $formHandlerFactory, TranslatorInterface $translator, EventDispatcherInterface $eventDispatcher)
    {
        $this->formHandlerFactory = $formHandlerFactory;
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBlueprint $form, $request = null, $entities = array(), $formView = null)
    {
        if (!$formView) {
            $formView = new FormView();
            if (isset($this->translator)) {
                $formView->setTranslator($this->translator);
            }
        }

        if (class_exists('AV\Validation\Validator')) {
            $validator = new Validator();
            if (isset($this->translator)) {
                $validator->setTranslator($this->translator);
            }
            if (isset($this->eventDispatcher)) {
                $validator->setEventDispatcher($this->eventDispatcher);
            }

            $validatorExtension = new AVValidatorExtension($validator);

            if (isset($this->container)) {
                $validatorExtension->setContainer($this->container);
            }
        }
        else {
            $validatorExtension = null;
        }

        $formHandler = $this->formHandlerFactory->buildForm($form, $formView, $validatorExtension);

        if (!is_array($entities)) {
            $entities = array($entities);
        }
        foreach ($entities as $entity) {
            $formHandler->bindEntity($entity);
        }

        if ($request) {
            $formHandler->handleRequest($request);
        }

        return $formHandler;
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