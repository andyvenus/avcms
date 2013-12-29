<?php

namespace AVCMS\Core\Form;

use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * Class FormBuilder
 * @package AVCMS\Core\Form
 * @todo HTML Secure
 */
class FormBuilder implements Validatable
{

    /**
     * @var array The form's current data
     */
    protected $params;

    /**
     * @var array The form elements/fields
     */
    protected $fields;

    /**
     * @var bool
     */
    protected $submitted = false;

    /**
     * @var
     */
    protected $entities;

    /**
     * @var bool
     */
    protected $request_handled;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param $label
     * @param $name
     * @param null $default_value
     * @param array $attr
     * @return $this
     */
    public function addTextInput($label, $name, $default_value = null, $attr = array())
    {
        $this->addInput($label, $name, 'text', $default_value, $attr);

        return $this;
    }

    /**
     * @param $label
     * @param $name
     * @param string $type
     * @param null $default_value
     * @param array $attr
     * @return $this
     */
    public function addInput($label, $name, $type = 'text', $default_value = null, $attr = array())
    {

        $field = array(
            'fieldtype' => 'input',
            'type' => $type,
            'attributes' => $attr,
            'label' => $label,
            'name' => $name
        );

        $this->params[$field['name']] = $default_value;
        $this->fields[$field['name']] = $field;

        return $this;
    }

    /**
     * @param $label
     * @param $name
     * @param string $default_value
     * @param array $attr
     * @return $this
     */
    public function addTextarea($label, $name, $default_value = '', $attr = array())
    {
        $field = array(
            'fieldtype' => 'textarea',
            'attributes' => $attr,
            'label' => $label,
            'name' => $name,
        );

        $this->params[$field['name']] = $default_value;
        $this->fields[$field['name']] = $field;

        return $this;
    }

    /**
     * @param $label
     * @param $name
     * @param array $options
     * @param null $selected
     * @param array $attr
     * @return $this
     */
    public function addSelect($label, $name, $options = array(), $selected = null, $attr = array())
    {

        $field = array(
            'fieldtype' => 'select',
            'attributes' => $attr,
            'label' => $label,
            'name' => $name,
            'options' => $options,
        );

        $this->params[$field['name']] = $selected;
        $this->fields[$field['name']] = $field;

        return $this;
    }

    /**
     * @param $label
     * @param $name
     * @param string $type
     * @param array $attr
     */
    public function addButton($label, $name, $type = 'button', $attr = array()) {

        $field = array(
            'fieldtype' => 'button',
            'type' => $type,
            'attributes' => $attr,
            'label' => $label,
            'name' => $name
        );

        $this->params[$field['name']] = null;
        $this->fields[$field['name']] = $field;
    }

    /**
     * @param Request $request
     * @param bool $assign_to_entities
     */
    public function handleRequest(Request $request, $assign_to_entities = true)
    {
        $this->request_handled = true;
        $this->submitted = true;
        foreach ($this->params as $param => $value) {
            if (!$request->request->has($param)) {
                $this->submitted = false;
                break;
            }
            else {
                $params[$param] = $request->request->get($param);
            }
        }

        if ($this->submitted && !empty($params)) {
            $this->params = $params;
            if ($assign_to_entities) {
                $this->saveToEntity();
            }
        }
    }

    /**
     * @param $entity
     * @throws \Exception
     */
    public function assignEntity($entity) {
        if ($this->request_handled) {
            throw new \Exception("Cannot assign entity after handleRequest() has been called. Assign entities before");
        }
        $this->entities[] = $entity;
        if (!$this->submitted) {
            foreach ($this->params as $field => $value) {
                if (isset($entity->{$field})) {
                    $this->params[$field] = $entity->{$field};
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function saveToEntity()
    {
        foreach ($this->params as $field => $value) {
            $this->entities[0]->{$field} = $value; // @todo multi-entity support
        }

        return $this->entities[0];
    }

    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function isValid($validation_scope = Validator::SCOPE_SUB_SHARED)
    {
        if (!isset($this->validator)) {
            throw new \Exception("Validator not set in form");
        }
        else if (!$this->submitted) {
            throw new \Exception("Form was not submitted");
        }
        else {
            $this->validator->validate($this, $validation_scope);
            return $this->validator->isValid();
        }
    }

    /**
     * @return bool
     */
    public function submitted() {
        return $this->submitted;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->params;
    }

    /**
     * @param $param
     * @return string
     */
    public function getParameter($param)
    {
        if (isset($this->params[$param])) {
            return $this->params[$param];
        }
        else {
            return null;
        }
    }

    public function getValidationRules(Validator $validator)
    {
        foreach ($this->entities as $entity) {
            $validator->addSubValidation($entity);
        }

        $this->validationRules($validator);

        return $validator;
    }

    public function validationRules(Validator $validator)
    {
        return $validator;
    }

    public function getErrors()
    {
        if (isset($this->validator)) {
            return $this->validator->getErrors();
        }
        else {
            throw new \Exception("Can't get errors: no validation object set");
        }
    }
} 