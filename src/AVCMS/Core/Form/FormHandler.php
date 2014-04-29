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
use AVCMS\Core\Form\Type\TypeHandler;
use AVCMS\Core\Form\Type\TypeInterface;
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
     * @var string The form submission method
     */
    protected $method = 'POST';

    /**
     * @var bool State of the form
     */
    protected $submitted = false;

    /**
     * @var array The entities that have been assigned
     */
    protected $entities = array();

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
     * @var array
     */
    protected $errors = array();

    /**
     * @param FormBlueprintInterface $form
     * @param \AVCMS\Core\Form\RequestHandler\RequestHandlerInterface|null $request_handler
     * @param EntityProcessor $entity_processor
     * @param \AVCMS\Core\Form\Type\TypeHandler $type_handler
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $event_dispatcher
     */
    public function __construct(
        FormBlueprintInterface $form,
        RequestHandlerInterface $request_handler = null,
        EntityProcessor $entity_processor = null,
        TypeHandler $type_handler = null,
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

        if ($type_handler) {
            $this->type_handler = $type_handler;
        }
        else {
            $this->type_handler = new TypeHandler();
        }

        if ($event_dispatcher) {
            $this->event_dispatcher = $event_dispatcher;
            $event = new FormHandlerConstructEvent($this, $this->form);
            $this->event_dispatcher->dispatch('form_handler.construct', $event);
            $this->form = $event->getFormBlueprint();
        }

        $this->fields = $this->getDefaultOptions($form->getAll());
        $this->data = $form->getDefaultData();

        $this->method = $form->getMethod();
        $this->action = $form->getAction();
        $this->form_name = $form->getName();

        if ($this->hasFieldOfType('file')) {
            $this->encoding = 'multipart/form-data';
        }
    }

    /**
     *  Get the default options for the fields for their type
     */
    protected function getDefaultOptions($fields)
    {
        $fields_updated = array();
        foreach ($fields as $field_name => $field) {
            $fields_updated[$field_name] = $this->type_handler->getDefaultOptions($field);
        }

        return $fields_updated;
    }

    /**
     * @return FormBlueprintInterface
     */
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
     * Add an entity to the form. The entities values will be used to fill the form and the forms data
     * will be assigned to the entity when saveToEntities is called
     *
     * @param $entity
     * @param null $fields
     * @param bool $validatable
     * @param null $id
     * @throws \Exception
     */
    public function bindEntity($entity, $fields = null, $validatable = true, $id = null)
    {
        if ($this->submitted) {
            throw new \Exception("Entities cannot be assigned after FormHandler::handleRequest has been called");
        }

        $this->entities[] = array('entity' => $entity, 'fields' => $fields, 'validatable' => $validatable);

        $entity_data = $this->entity_processor->getFromEntity($entity, array_keys($this->fields), $fields);

        $this->data = array_merge($this->data, $entity_data);
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Use a request handler to get data from a request and store the values that match fields
     * in the form blueprint
     *
     * @param null $request
     */
    public function handleRequest($request = null)
    {
        $this->submitted = true;

        $request_data = $this->request_handler->handleRequest($this, $request);

        foreach ($this->fields as $field) {
            $field_name = $field['name'];
            if (isset($request_data[ $field_name ])) {
                $field_submitted = $this->type_handler->isValidRequestData($field, $request_data[$field_name]);
            }
            else {
                $field_submitted = $this->type_handler->allowUnsetRequest($field);
            }

            // Form was not submitted
            if ($field_submitted === false) {
                $this->submitted = false;
                break;
            }
            else {
                if (isset($request_data[$field_name])) {
                    $valid_request_data[$field_name] = $this->type_handler->processRequestData($field, $request_data[$field_name]);
                }
                else {
                    $data = $this->type_handler->getUnsetRequestData($field);

                    if ($data !== null) {
                        $valid_request_data[$field_name] = $data;
                    }
                }
            }
        }

        if ($this->submitted == true && isset($valid_request_data)) {
            $this->data = $valid_request_data;
            $this->setRequiredFieldErrors();
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

    /**
     * Save the current form data to the assigned entities
     */
    public function saveToEntities()
    {
        foreach ($this->entities as $entity) {
            $this->entity_processor->saveToEntity($entity['entity'], $this->data, $entity['fields']);
        }
    }

    /**
     * Check if the form has been submitted
     *
     * @return bool
     */
    public function isSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Get a processed field from the form
     *
     * @param $name
     * @return null
     */
    public function getField($name)
    {
        return $this->getProcessedField($name);
    }

    /**
     * Process all fields and return them
     *
     * @return array
     */
    public function getProcessedFields()
    {
        $fields = array();
        foreach ($this->fields as $field_name => $field) {
            $fields[$field_name] = $this->getProcessedField($field_name);
        }

        return $fields;
    }

    /**
     * Process a field and return it
     *
     * @param $field_name
     * @return bool
     */
    public function getProcessedField($field_name)
    {
        if (!isset($this->fields[$field_name])) {
            return false;
        }

        $field = $this->fields[$field_name];

        $field['has_error'] = $this->fieldHasError($field_name);

        return $this->type_handler->makeView($field, $this->data, $this);
    }

    /**
     * Check if a field has an error
     *
     * @param $field_name
     * @return bool
     */
    public function fieldHasError($field_name)
    {
        if (!$this->isSubmitted()) {
            return false;
        }

        if (isset($this->validator) && $this->validator->fieldHasError($field_name)) {
            return true;
        }

        foreach ($this->errors as $error) {
            if ($error->getParam() == $field_name) {
                return true;
            }
        }
    }

    /**
     * Check if the form contains any fields of a certain type like "select" or "textarea"
     *
     * @param $field_type
     * @return bool
     */
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
     * @return mixed
     */
    public function getData($name = null)
    {
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

    public function getTypeHandler()
    {
        return $this->type_handler;
    }

    /**
     * Set the form submission method
     *
     * @param $method string Value: POST/GET
     * @throws \Exception
     */
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

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param $action string The url the form will submit to
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return null|string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->form_name;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Set the view used to render the form
     *
     * @param FormViewInterface $view
     */
    public function setFormView(FormViewInterface $view)
    {
        $this->form_view = $view;
    }

    /**
     * Assign the form data to the view
     *
     * @return FormView
     */
    public function createView()
    {
        if (!$this->form_view) {
            $this->form_view = new FormView();
        }

        $this->form_view->setFields($this->getProcessedFields());
        $this->form_view->setMethod($this->getMethod());
        $this->form_view->setName($this->getName());
        $this->form_view->setEncoding($this->getEncoding());

        if ($this->submitted && isset($this->validator)) {
            $this->form_view->setErrors($this->getValidationErrors());
        }

        return $this->form_view;
    }

    /**
     * Set a validator wrapped in a ValidatorExtension class
     *
     * @param ValidatorExtension $validator
     */
    public function setValidator(ValidatorExtension $validator)
    {
        $this->validator = $validator;
        $this->validator->setFormHandler($this);
    }

    public function getValidator()
    {
        if (isset($this->validator)) {
            return $this->validator;
        }
        else {
            throw new \Exception('Cannot get validator, no validator assigned');
        }
    }

    /**
     * Check if the form is valid using the validator if it is set and checking for any
     * custom errors. Will save data to entities.
     *
     * @param null $scope
     * @param null $options
     * @return bool
     * @throws \Exception
     */
    public function isValid($scope = null, $options = null)
    {
        if (!$this->isSubmitted()) {
            return false;
        }

        $this->saveToEntities();

        if ((isset($this->validator) && $this->validator->isValid($scope, $options) && empty($this->errors)) || (!isset($this->validator) && empty($this->errors))) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Check each field to see if they're required. If they have no data set, assign the error
     * TODO: Recursive support for collection
     */
    public function setRequiredFieldErrors()
    {
        foreach ($this->fields as $field) {
            if (isset($field['options']['required']) && $field['options']['required'] === true) {
                if (!isset($this->data[ $field['name'] ]) || !$this->data[ $field['name'] ] ) {
                    if (isset($field['options']['label'])) {
                        $label = $field['options']['label'];
                    }
                    else {
                        $label = $field['name'];
                    }
                    $this->errors[] = new FormError($field['name'], "Field {field_label} must be set", true, array('field_label' => $label));
                }
            }
        }
    }

    /**
     * Add custom errors to the form. Must be an array of FormError objects
     *
     * @param $errors FormError[]
     * @throws \Exception
     */
    public function addCustomErrors($errors)
    {
        if ($errors) {
            foreach ($errors as $error) {
                if (!is_a($error, 'AVCMS\Core\Form\FormError')) {
                    throw new \Exception('Custom errors must be AVCMS\Core\Form\FormError objects');
                }
                else {
                    $this->errors[] = $error;
                }
            }
        }
    }

    /**
     * Get the errors from the validator
     *
     * @return mixed
     * @throws \Exception
     */
    public function getValidationErrors()
    {

        $errors = $this->errors;

        if (isset($this->validator)) {
            $validator_errors = $this->validator->getErrors();
            $errors = array_merge($errors, $validator_errors);
        }

        return $errors;
    }

    public function __get($name)
    {
        return $this->getData($name);
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
}