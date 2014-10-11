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
use AVCMS\Core\Form\Exception\BadMethodCallException;
use AVCMS\Core\Form\Exception\InvalidArgumentException;
use AVCMS\Core\Form\RequestHandler\RequestHandlerInterface;
use AVCMS\Core\Form\RequestHandler\StandardRequestHandler;
use AVCMS\Core\Form\Transformer\TransformerManager;
use AVCMS\Core\Form\Type\TypeHandler;
use AVCMS\Core\Form\ValidatorExtension\ValidatorExtension;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class FormHandler
 * @package AVCMS\Core\FormBlueprint
 *
 * Handles form requests, validation, data transformation and allows forms to interact with entities
 */
class FormHandler
{
    /**
     * @var FormBlueprintInterface
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
    protected $entityProcessor;

    /**
     * @var FormView A FormView instance that helps render the form
     */
    protected $formView;

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
    protected $formName;

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
    protected $requestHandler;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @var TransformerManager
     */
    protected $transformerManager;

    /**
     * @var Type\TypeHandler
     */
    protected $typeHandler;

    /**
     * @param FormBlueprintInterface $form
     * @param \AVCMS\Core\Form\RequestHandler\RequestHandlerInterface|null $requestHandler
     * @param EntityProcessor $entityProcessor
     * @param \AVCMS\Core\Form\Type\TypeHandler $typeHandler
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        FormBlueprintInterface $form,
        RequestHandlerInterface $requestHandler = null,
        EntityProcessor $entityProcessor = null,
        TypeHandler $typeHandler = null,
        EventDispatcherInterface $eventDispatcher = null
    )
    {
        $this->form = $form;

        if ($entityProcessor) {
            $this->entityProcessor = $entityProcessor;
        }
        else {
            $this->entityProcessor = new GetterSetterEntityProcessor();
        }

        if ($requestHandler) {
            $this->requestHandler = $requestHandler;
        }
        else {
            $this->requestHandler = new StandardRequestHandler();
        }

        if ($typeHandler) {
            $this->typeHandler = $typeHandler;
        }
        else {
            $this->typeHandler = new TypeHandler();
        }

        if ($eventDispatcher) {
            $this->eventDispatcher = $eventDispatcher;
            $event = new FormHandlerConstructEvent($this, $this->form);
            $this->eventDispatcher->dispatch('form_handler.construct', $event);
            $this->form = $event->getFormBlueprint();
        }

        $this->fields = $this->getDefaultOptions($form->getAll());
        $this->data = $form->getDefaultData();

        $this->method = $form->getMethod();
        $this->action = $form->getAction();
        $this->formName = $form->getName();

        if ($this->hasFieldOfType('file')) {
            $this->encoding = 'multipart/form-data';
        }
    }

    /**
     *  Get the default options for the fields for their type
     *
     * @param $fields array An array of field data
     * @return array The fields, updated with default data
     */
    protected function getDefaultOptions(array $fields)
    {
        $fields_updated = array();
        foreach ($fields as $field_name => $field) {
            $fields_updated[$field_name] = $this->typeHandler->getDefaultOptions($field);
        }

        return $fields_updated;
    }

    /**
     * Get the form blueprint
     *
     * @return FormBlueprintInterface
     */
    public function getFormBlueprint()
    {
        return $this->form;
    }

    /**
     * Set the default values of matching fields
     *
     * @param array $defaultValues
     */
    public function setDefaultValues(array $defaultValues)
    {
        foreach ($defaultValues as $name => $value) {
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
            throw new BadMethodCallException("Entities cannot be assigned after FormHandler::handleRequest has been called");
        }

        $this->entities[] = array('entity' => $entity, 'fields' => $fields, 'validatable' => $validatable);

        $entityData = $this->entityProcessor->getFromEntity($entity, array_keys($this->fields), $fields);

        $entityData = $this->transformToFormData($entityData);

        $this->data = array_replace_recursive($this->data, $entityData);
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

        $requestData = $this->requestHandler->handleRequest($this, $request);

        foreach ($this->fields as $field) {
            $fieldName = $field['name'];
            if (isset($requestData[ $fieldName ])) {
                $field_submitted = $this->typeHandler->isValidRequestData($field, $requestData[$fieldName]);
            }
            else {
                $field_submitted = $this->typeHandler->allowUnsetRequest($field);
            }

            // Form was not submitted
            if ($field_submitted === false) {
                $this->submitted = false;
                break;
            }
            else {
                if (isset($requestData[$fieldName])) {
                    $validRequestData[$fieldName] = $this->typeHandler->processRequestData($field, $requestData[$fieldName]);
                }
                else {
                    $data = $this->typeHandler->getUnsetRequestData($field);

                    if ($data !== null) {
                        $validRequestData[$fieldName] = $data;
                    }
                }
            }
        }

        if ($this->submitted == true && isset($validRequestData)) {
            $this->data = $validRequestData;
            $this->setRequiredFieldErrors();
        }
        else {
            $this->submitted = false;
        }

        if (isset($this->eventDispatcher)) {
            $event = new FormHandlerRequestEvent($this, $request, $this->data);
            $this->eventDispatcher->dispatch('form_handler.request', $event);
            $this->data = $event->getFormData();
        }
    }

    /**
     * Save the current form data to the assigned entities
     */
    public function saveToEntities()
    {
        $data = $this->transformFromFormData($this->data);

        foreach ($this->entities as $entity) {
            $this->entityProcessor->saveToEntity($entity['entity'], $data, $entity['fields']);
        }
    }

    /**
     * Save the form data to cloned entities and retrieve them.
     *
     * Useful for getting entities with the data assigned without affecting
     * the original entities
     *
     * @return array
     */
    public function saveToAndGetClonedEntities()
    {
        $data = $this->transformFromFormData($this->data);

        $cloned_entities = array();
        foreach ($this->entities as $entity) {
            $entity['entity'] = clone $entity['entity'];
            $cloned_entities[] = $entity;
            $this->entityProcessor->saveToEntity($entity['entity'], $data, $entity['fields']);
        }

        return $cloned_entities;
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
        foreach ($this->fields as $fieldName => $field) {
            $fields[$fieldName] = $this->getProcessedField($fieldName);
        }

        return $fields;
    }

    /**
     * Process a field and return it
     *
     * @param $fieldName
     * @return bool
     */
    public function getProcessedField($fieldName)
    {
        if (!isset($this->fields[$fieldName])) {
            return false;
        }

        $field = $this->fields[$fieldName];

        $field['has_error'] = $this->fieldHasError($fieldName);

        return $this->typeHandler->makeView($field, $this->data, $this);
    }

    /**
     * Check if a field has an error
     *
     * @param $fieldName
     * @return bool
     */
    public function fieldHasError($fieldName)
    {
        if (!$this->isSubmitted()) {
            return false;
        }

        if (isset($this->validator) && $this->validator->fieldHasError($fieldName)) {
            return true;
        }

        foreach ($this->errors as $error) {
            if ($error->getParam() == $fieldName) {
                return true;
            }
        }
    }

    /**
     * Check if the form contains any fields of a certain type like "select" or "textarea"
     *
     * @param $fieldType
     * @return bool
     */
    public function hasFieldOfType($fieldType)
    {
        foreach ($this->fields as $field) {
            if ($field['type'] == $fieldType) {
                return true;
            }
        }

        return false;
    }

    public function hasFieldWithName($fieldName)
    {
        return isset($this->fields[$fieldName]);
    }

    /**
     * Get the values of all the form fields or a single form field
     *
     * @param null $name
     * @param bool $transform
     * @return mixed
     */
    public function getData($name = null, $transform = true)
    {
        $data = $this->data;
        if ($transform == true) {
            $data = $this->transformFromFormData($this->data);
        }

        if ($name === null) {
            return $data;
        }
        else {
            if (isset($data[$name])) {
                return $data[$name];
            }
            else {
                return null;
            }
        }
    }

    /**
     * Set the value of a form field
     *
     * @param $name
     * @param $value
     */
    public function setData($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Merge an array of data with the existing data
     *
     * @param $data
     */
    public function mergeData($data)
    {
        $this->data = array_replace_recursive($this->data, $data);
    }

    /**
     * Get the active type handler
     *
     * @return TypeHandler
     */
    public function getTypeHandler()
    {
        return $this->typeHandler;
    }

    /**
     * Set the form submission method
     *
     * @param $method string Value: POST/GET
     * @throws InvalidArgumentException
     */
    public function setMethod($method)
    {
        if ($method != 'POST' && $method != 'GET')
        {
            throw new InvalidArgumentException("Unknown method type '".$method);
        }
        else {
            $this->method = $method;
        }
    }

    /**
     * Get the form method e.g. GET or POST
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the URL the form will submit to
     *
     * @param $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get the URL the form will submit to
     *
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
        return $this->formName;
    }

    /**
     * Get the form encoding
     *
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
        $this->formView = $view;
    }

    /**
     * Assign the form data to the view
     *
     * @return FormView
     */
    public function createView()
    {
        if (!$this->formView) {
            $this->formView = new FormView();
        }

        $this->formView->setFormBlueprint($this->form);
        $this->formView->setFields($this->getProcessedFields());
        $this->formView->setSections($this->form->getSections());
        $this->formView->setMethod($this->getMethod());
        $this->formView->setName($this->getName());
        $this->formView->setEncoding($this->getEncoding());
        $this->formView->setAction($this->getAction());
        $this->formView->setSubmitted($this->isSubmitted());

        //if ($this->submitted && isset($this->validator)) {
            $this->formView->setErrors($this->getValidationErrors());
        //}

        return $this->formView;
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

    /**
     * Get the assigned form validator
     *
     * @return ValidatorExtension
     * @throws BadMethodCallException
     */
    public function getValidator()
    {
        if (isset($this->validator)) {
            return $this->validator;
        }
        else {
            throw new BadMethodCallException('Cannot get validator, no validator assigned to this form');
        }
    }

    /**
     * Check if the form is valid using the validator if it is set and checking for any
     * custom errors. Will save data to entities.
     *
     * @param null $scope
     * @param null $options
     * @return bool
     */
    public function isValid($scope = null, $options = null)
    {
        if (!$this->isSubmitted()) {
            return false;
        }

        // Check for internal errors & validator errors
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
    protected function setRequiredFieldErrors()
    {
        foreach ($this->fields as $field) {
            if (isset($field['options']['required']) && $field['options']['required'] === true) {
                if ($this->typeHandler->allowUnsetRequest($field) === false && (!isset($this->data[ $field['name'] ]) || !$this->data[ $field['name'] ] )) {
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
     * @throws InvalidArgumentException
     */
    public function addCustomErrors($errors)
    {
        if ($errors) {
            foreach ($errors as $error) {
                if (!is_a($error, 'AVCMS\Core\Form\FormError')) {
                    throw new InvalidArgumentException('Custom errors must be AVCMS\Core\Form\FormError objects');
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

    /**
     * Get the entity processor
     *
     * @return GetterSetterEntityProcessor
     */
    public function getEntityProcessor()
    {
        return $this->entityProcessor;
    }

    /**
     * Set the transformer manager
     *
     * @param TransformerManager $transformerManager
     */
    public function setTransformerManager(TransformerManager $transformerManager)
    {
        $this->transformerManager = $transformerManager;
    }

    /**
     * Transform raw/entity data into data suitable for the form
     *
     * @param $data
     * @return mixed
     */
    protected function transformToFormData($data)
    {
        return $this->transformData('to', $data);
    }

    /**
     * Transform form data into raw data and data suitable for entities
     *
     * @param $data
     * @return mixed
     */
    protected function transformFromFormData($data)
    {
        return $this->transformData('from', $data);
    }

    /**
     * Transform all given data that has an ['options']['transform'] value set
     *
     * @param $type
     * @param $data
     * @return mixed
     */
    protected function  transformData($type, $data)
    {
        foreach ($data as $field => $value) {
            if (isset($this->fields[$field]['options']['transform'])) {
                $data[$field] = $this->transformerManager->{$type.'Form'}($value, $this->fields[$field]['options']['transform']);
            }
        }

        return $data;
    }
}