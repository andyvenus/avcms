<?php

namespace AVCMS\Core\Model;

use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;

class Entity implements Validatable {

    protected $fields = array();

    protected $data = array();

    protected $other_data = array();

    public function __set($name, $value) {
        if (in_array($name, $this->fields)) {
            $this->data[$name] = $value;
        }
        else {
            $this->other_data[$name] = $value;
        }
    }

    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        else {
            return null;
        }
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function getData()
    {
        return $this->data;
    }

    public function hasField($name)
    {
        return in_array($name, $this->fields);
    }

    public function getOther($name)
    {
        return $this->other_data[$name];
    }

    public function addSubEntity($name, Entity $entity)
    {
        $this->data[$name] = $entity;
    }

    public function addField($field)
    {
        $this->fields[] = $field;
    }

    public function validationRules(Validator $validator)
    {
        return $validator;
    }

    public function getValidationRules(Validator $validator)
    {
        return $this->validationRules($validator);
    }

    public function getParameters()
    {
        return $this->getData();
    }
}