<?php
/**
 * User: Andy
 * Date: 03/09/2014
 * Time: 15:11
 */

namespace AVCMS\Core\Form;

use AVCMS\Core\Form\EntityProcessor\EntityProcessor;
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
        $this->request_handler = $requestHandler;
        $this->entity_processor = $entityProcessor;
        $this->transformer_manager = $transformerManager;
        $this->event_dispatcher = $eventDispatcher;
        $this->type_handler = $typeHandler;
    }

    public function buildForm(FormBlueprint $form, FormViewInterface $formView = null, ValidatorExtension $validator = null)
    {
        $form_handler = new FormHandler($form, $this->request_handler, $this->entity_processor, $this->type_handler, $this->event_dispatcher);

        if ($validator) {
            $form_handler->setValidator($validator);
        }

        if (!$formView) {
            $formView = new FormView();
        }

        $form_handler->setFormView($formView);
        $form_handler->setTransformerManager($this->transformer_manager);

        return $form_handler;
    }
} 