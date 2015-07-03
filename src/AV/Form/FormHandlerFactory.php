<?php
/**
 * User: Andy
 * Date: 03/09/2014
 * Time: 15:11
 */

namespace AV\Form;

use AV\Form\EntityProcessor\EntityProcessorInterface;
use AV\Form\Event\FilterNewFormEvent;
use AV\Form\RequestHandler\RequestHandlerInterface;
use AV\Form\RestoreDataHandler\RestoreDataHandlerInterface;
use AV\Form\Transformer\TransformerManager;
use AV\Form\Type\TypeHandler;
use AV\Form\ValidatorExtension\ValidatorExtensionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FormHandlerFactory
{
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @var RequestHandlerInterface
     */
    protected $requestHandler;

    /**
     * @var EntityProcessorInterface
     */
    protected $entityProcessor;

    /**
     * @var TransformerManager
     */
    protected $transformerManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var TypeHandler
     */
    protected $typeHandler;

    /**
     * @var RestoreDataHandlerInterface
     */
    protected $restoreDataHandler;

    public function __construct(RequestHandlerInterface $requestHandler, EntityProcessorInterface $entityProcessor, TransformerManager $transformerManager, EventDispatcherInterface $eventDispatcher = null, TypeHandler $typeHandler = null, RestoreDataHandlerInterface $restoreDataHandler = null)
    {
        $this->requestHandler = $requestHandler;
        $this->entityProcessor = $entityProcessor;
        $this->transformerManager = $transformerManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->typeHandler = $typeHandler;
        $this->restoreDataHandler = $restoreDataHandler;
    }

    public function buildForm(FormBlueprintInterface $form, FormViewInterface $formView = null, ValidatorExtensionInterface $validator = null)
    {
        if ($this->eventDispatcher !== null) {
            $event = new FilterNewFormEvent($form, $formView, $validator);
            $this->eventDispatcher->dispatch('form_factory.create', $event);

            $form = $event->getFormBlueprint();
            $formView = $event->getFormView();
            $validator = $event->getValidator();
        }

        $formHandler = new FormHandler($form, $this->requestHandler, $this->entityProcessor, $this->typeHandler, $this->eventDispatcher, $this->restoreDataHandler);

        if ($validator) {
            $formHandler->setValidator($validator);
        }

        if (!$formView) {
            $formView = new FormView();
        }

        $formHandler->setFormView($formView);
        $formHandler->setTransformerManager($this->transformerManager);

        return $formHandler;
    }
} 
