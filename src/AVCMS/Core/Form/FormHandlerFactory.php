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

    public function __construct(RequestHandlerInterface $request_handler, EntityProcessor $entity_processor, TransformerManager $transformer_manager, EventDispatcherInterface $event_dispatcher = null, TypeHandler $type_handler = null)
    {
        $this->request_handler = $request_handler;
        $this->entity_processor = $entity_processor;
        $this->transformer_manager = $transformer_manager;
        $this->event_dispatcher = $event_dispatcher;
        $this->type_handler = $type_handler;
    }

    public function buildForm(FormBlueprint $form, FormViewInterface $form_view = null, ValidatorExtension $validator = null)
    {
        $form_handler = new FormHandler($form, $this->request_handler, $this->entity_processor, $this->type_handler, $this->event_dispatcher);

        if ($validator) {
            $form_handler->setValidator($validator);
        }

        if (!$form_view) {
            $form_view = new FormView();
        }

        $form_handler->setFormView($form_view);
        $form_handler->setTransformerManager($this->transformer_manager);

        return $form_handler;
    }
} 