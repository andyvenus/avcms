<?php
/**
 * User: Andy
 * Date: 03/09/2014
 * Time: 15:11
 */

namespace AVCMS\Core\Form;

use AVCMS\Core\Form\EntityProcessor\EntityProcessor;
use AVCMS\Core\Form\Event\FilterNewFormEvent;
use AVCMS\Core\Form\RequestHandler\RequestHandlerInterface;
use AVCMS\Core\Form\Transformer\TransformerManager;
use AVCMS\Core\Form\Type\TypeHandler;
use AVCMS\Core\Form\ValidatorExtension\ValidatorExtension;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FormHandlerFactory
{
    protected $translator;

    public function __construct(RequestHandlerInterface $requestHandler, EntityProcessor $entityProcessor, TransformerManager $transformerManager, EventDispatcherInterface $eventDispatcher = null, TypeHandler $typeHandler = null)
    {
        $this->requestHandler = $requestHandler;
        $this->entityProcessor = $entityProcessor;
        $this->transformerManager = $transformerManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->typeHandler = $typeHandler;
    }

    public function buildForm(FormBlueprintInterface $form, FormViewInterface $formView = null, ValidatorExtension $validator = null)
    {
        if ($this->eventDispatcher !== null) {
            $event = new FilterNewFormEvent($form, $formView, $validator);
            $this->eventDispatcher->dispatch('create.form', $event);

            $form = $event->getFormBlueprint();
            $formView = $event->getFormView();
            $validator = $event->getValidator();
        }

        $formHandler = new FormHandler($form, $this->requestHandler, $this->entityProcessor, $this->typeHandler, $this->eventDispatcher);

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