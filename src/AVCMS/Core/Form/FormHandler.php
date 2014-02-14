<?php
/**
 * User: Andy
 * Date: 12/01/2014
 * Time: 11:02
 */

namespace AVCMS\Core\Form;

use AVCMS\Core\Form\EntityProcessor\EntityProcessor;
use AVCMS\Core\Form\EntityProcessor\GetterSetterEntityProcessor;
use AVCMS\Core\Form\Event\FormHandlerConstructEvent;
use AVCMS\Core\Form\Event\FormHandlerRequestEvent;
use AVCMS\Core\Form\RequestHandler\RequestHandlerInterface;
use AVCMS\Core\Form\RequestHandler\StandardRequestHandler;
use AVCMS\Core\Form\ValidatorExtension\ValidatorExtension;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class FormHandler
 * @package AVCMS\Core\FormBlueprint
 *
 * Handles form requests, form validation and allows forms to interact with entities
 */
class FormHandler
{
    /**
     * @var FormBlueprint
     */
    protected $form;

    /**
     * @var array The values of the form fields (from default values, entities or the request)
     */
    protected $data;

    /**
     * @var string FormBlueprint submit method
     */
    protected $method = 'POST';

    /**
     * @var bool State of the form
     */
    protected $submitted = false;

    /**
     * @var array The entities that have been assigned
     */
    protected $entities;

    /**
     * @var GetterSetterEntityProcessor The entity processor to set and get values from an entity
     */
    protected $entity_processor;

    /**
     * @var FormView A FormView instance that helps render the form
     */
    protected $form_view;

    /**
     * @var ValidatorExtension A validator object
     */
    protected $validator;

    /**
     * @var string The URL the form will submit to
     */
    protected $action = null;

    /**
     * @var string
     */
    protected $form_name;

    /**
     * @var array The form fields
     */
    protected $fields;

    /**
     * @var string
     */
    protected $encoding = 'application/x-www-form-urlencoded';

    /**
     * @var RequestHandler\StandardRequestHandler
     */
    protected $request_handler;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $event_dispatcher;

    /**
     * @param FormBlueprintInterface $form
     * @param \AVCMS\Core\Form\RequestHandler\RequestHandlerInterface|null $request_handler
     * @param EntityProcessor $entity_processor
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $event_dispatcher
     */
    public function __construct(
        FormBlueprintInterface $form,
        RequestHandlerInterface $request_handler = null,
        EntityProcessor $entity_processor = null,
        EventDispatcher $event_dispatcher = null
    )
    {
        $this->form = $form;

        if ($entity_processor) {
            $this->entity_processor = $entity_processor;
        }
        else {
            $this->entity_processor = new GetterSetterEntityProcessor();
        }

        if ($request_handler) {
            $this->request_handler = $request_handler;
        }
        else {
            $this->request_handler = new StandardRequestHandler();
        }

        if ($event_dispatcher) {
            $this->event_dispatcher = $event_dispatcher;
            $event = new FormHandlerConstructEvent($this, $this->form);
            $this->event_dispatcher->dispatch('form_handler.construct', $event);
            $this->form = $event->getFormBlueprint();
        }

        $this->fields = $form->getAll();
        $this->data = $form->getDefaultData();

        $this->method = $form->getMethod();
        $this->action = $form->getAction();
        $this->form_name = $form->getName();

        if ($this->hasFieldOfType('file')) {
            $this->encoding = 'multipart/form-data';
        }
    }

    public function getForm()
    {
        return $this->form;
    }

    public function setDefaultValues(array $default_values)
    {
        foreach ($default_values as $name => $value) {
            if (isset($this->fields[$name])) {
                $this->data[$name] = $value;
            }
        }
    }

    /**
     * @param $entity
     * @param null $fields
     * @param bool $validatable
     * @param null $id
     * @throws \Exception
     */
    public function addEntity($entity, $fields = null, $validatable = true, $id = null)
    {
        if ($this->submitted) {
            throw new \Exception("Entities cannot be assigned after handleRequest has been called");
        }

        $this->entities[] = array('entity' => $entity, 'fields' => $fields, 'validatable' => $validatable);

        $entity_data = $this->entity_processor->getFromEntity($entity, array_keys($this->fields), $fields);

        $this->data = array_merge($this->data, $entity_data);
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function handleRequest($request = null)
    {
        $this->submitted = true;

        $request_data = $this->request_handler->handleRequest($this, $request);

        foreach ($this->fields as $field) {
            if (!isset($request_data[ $field['name'] ]) && $field['type'] != 'checkbox') {
                $this->submitted = false;
                break;
            }
            else {
                $req_data[ $field['name'] ] = $request_data[ $field['name'] ];
            }
        }

        if ($this->submitted == true && isset($req_data)) {
            $this->data = $req_data;
        }
        else {
            $this->submitted = false;
        }

        if (isset($this->event_dispatcher)) {
            $event = new FormHandlerRequestEvent($this, $request, $this->data);
            $this->event_dispatcher->dispatch('form_handler.request', $event);
            $this->data = $event->getFormData();
        }
    }

    public function saveToEntities()
    {
        if (!isset($this->entities)) {
            return;
        }

        foreach ($this->entities as $entity) {
            $this->entity_processor->saveToEntity($entity['entity'], $this->data, $entity['fields']);
        }
    }

    public function isSubmitted()
    {
        return $this->submitted;
    }

    public function getFields()
    {
        return $this->processFieldsCollection($this->fields, $this->data);
    }

    public function getField($name)
    {
        if (isset($this->fields[$name])) {
            return $this->getProcessedField($this->fields[$name], $this->data);
        }

        return null;
    }

    protected function processFieldsCollection($field_collection, $data)
    {
        $fields = array();
        foreach ($field_collection as $field) {
            // Unnamed array fields
            if ($field['name'] === null) {
                if (!isset($i)) $i = 0;

                $field['name'] = $i;
                $fields[] = $this->getProcessedField($field, $data);

                $i++;
            }
            else {
                $fields[$field['name']] = $this->getProcessedField($field, $data);
            }
        }

        return $fields;
    }

    protected function getProcessedField($field, $data)
    {
        if (isset($data[$field['name']])) {
            $field['value'] = $data[$field['name']];
        }

        if (isset($field['fields']) && isset($field['value'])) {
            $field['fields'] = $this->processFieldsCollection($field['fields'], $field['value']);
        }

        if (isset($field['original_name'])) {
            $field['name'] = $field['original_name'];
        }

        return $field;
    }

    public function hasFieldOfType($field_type)
    {
        foreach ($this->fields as $field) {
            if ($field['type'] == $field_type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param null $name
     * @return array|null|string|\Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getData($name = null)
    {
        $this->data;

        if ($name === null) {
            return $this->data;
        }
        else {
            if (isset($this->data[$name])) {
                return $this->data[$name];
            }
            else {
                return null;
            }
        }
    }

    public function setMethod($method)
    {
        if ($method != 'POST' && $method != 'GET')
        {
            throw new \Exception("Unknown method type '".$method);
        }
        else {
            $this->method = $method;
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getName()
    {
        return $this->form_name;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function setFormView(FormViewInterface $view)
    {
        $this->form_view = $view;
    }

    public function createView()
    {
        if (!$this->form_view) {
            $this->form_view = new FormView();
        }

        $this->form_view->setFields($this->getFields());
        $this->form_view->setMethod($this->getMethod());
        $this->form_view->setName($this->getName());
        $this->form_view->setEncoding($this->getEncoding());

        if ($this->submitted && isset($this->validator)) {
            $this->form_view->setErrors($this->getValidationErrors());
        }

        return $this->form_view;
    }

    public function setValidatior(ValidatorExtension $validator)
    {
        $this->validator = $validator;
        $this->validator->setFormHandler($this);
    }

    public function isValid($scope = null, $options = null) {
        if (!isset($this->validator)) {
            throw new \Exception("Cannot check if a form is valid if no ValidationExtension has been assigned to the form handler");
        }
        elseif (!$this->isSubmitted()) {
            return false;
        }

        $this->saveToEntities();

        return $this->validator->isValid($scope, $options);
    }

    public function getValidationErrors()
    {
        if (!isset($this->validator)) {
            throw new \Exception("Cannot check if a form is valid if no ValidationExtension has been assigned to the form handler");
        }

        return $this->validator->getErrors();
    }
}