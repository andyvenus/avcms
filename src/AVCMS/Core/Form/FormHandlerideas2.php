<?php
/**
 * User: Andy
 * Date: 12/01/2014
 * Time: 11:02
 */

namespace AVCMS\Core\Form;

use AVCMS\Core\Form\EntityProcessor\EntityProcessor;
use AVCMS\Core\Form\EntityProcessor\GetterSetterEntityProcessor;
use AVCMS\Core\Form\ValidatorExtension\ValidatorExtension;

/**
 * Class FormHandler
 * @package AVCMS\Core\FormBlueprint
 *
 * Handles form requests, form validation and allows forms to interact with entities
 */
class FormHandlerideas
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
     * @param FormBlueprint $form
     * @param EntityProcessor $entity_processor
     */
    public function __construct(FormBlueprint $form, EntityProcessor $entity_processor = null)
    {
        $this->form = $form;

        if ($entity_processor) {
            $this->entity_processor = $entity_processor;
        }
        else {
            $this->entity_processor = new GetterSetterEntityProcessor();
        }

        $this->fields = $form->getAll();
        $this->data = $form->getDefaultData();

        $this->method = $form->getMethod();
        $this->action = $form->getAction();
        $this->form_name = $form->getName();
    }

    public function getData($name = null, $data = null)
    {
        if ($data === null) {
            $data = $this->data;
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

    public function handleRequest($request, $type = 'standard')
    {
        $this->submitted = true;

        if ($type == 'symfony') {
            if ($this->method == 'POST') {
                $param_bag = 'request';
            }
            else {
                $param_bag = 'query';
            }

            foreach ($this->fields as $field) {
                if (!$request->$param_bag->has($field['name']) && $field['type'] != 'checkbox') {
                    $this->submitted = false;
                    break;
                }
                else {
                    $req_data[$field['name']] = $request->$param_bag->get($field['name']);
                }
            }
        }
        elseif ($type == 'standard') {
            foreach ($this->fields as $field) {
                if (!isset($request[$field['name']]) && $field['type'] != 'checkbox') {
                    $this->submitted = false;
                    break;
                }
                else {
                    $req_data[$field['name']] = $request[$field['name']];
                }
            }
        }

        if ($this->submitted == true && isset($req_data)) {
            $this->data = $req_data;
        }
        else {
            $this->submitted = false;
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

    public function getField($name, $field_data = null, $data = null)
    {
        if ($field_data === null) {
            $field_data = $this->fields;
        }

        if (!isset($field_data[$name])) {
            return false;
        }

        $field = $field_data[$name];
        $field['value'] = $this->getData($name, $data);

        if (isset($field['fields'])) {
            $field['fields'] = $this->getFields($field['fields'], $field['value']);
        }

        if (isset($field['original_name'])) {
            $field['name'] = $field['original_name'];
        }

        return $field;
    }

    protected function getFields($field_data = null, $data = null)
    {
        if ($field_data === null) {
            $field_data = $this->fields;
        }

        $fields = array();
        foreach ($field_data as $field) {
            // Unnamed array fields
            if ($field['name'] === null) {
                if (!isset($i)) $i = 0;

                $fields[] = $this->getField($i, $field_data, $data);

                $i++;
            }
            else {
                $fields[$field['name']] = $this->getField($field['name'], $field_data, $data);
            }
        }

        return $fields;
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

    protected function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
} 