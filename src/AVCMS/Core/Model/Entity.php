<?php
/**
 * User: Andy
 * Date: 04/01/2014
 * Time: 15:46
 */

namespace AVCMS\Core\Model;

use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;

/**
 * Class Entity
 * @package AVCMS\Core\Model
 */
abstract class Entity implements Validatable
{
    /**
     * @var array The data this entity is holding
     */
    protected $data = array();

    /**
     * @var Entity[] Any entities assigned via the magic __get method
     */
    protected $sub_entities = array();

    /**
     * @param $param
     * @return null
     */
    protected function data($param)
    {
        if (isset($this->data[$param])) {
            return $this->data[$param];
        }
        else {
            return null;
        }
    }

    /**
     * @param $param
     * @param $value
     */
    protected function setData($param, $value)
    {
        $this->data[$param] = $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (empty($this->sub_entities)) {
            return $this->data;
        }
        else {
            $data = $this->data;
            foreach ($this->sub_entities as $sub_entity) {
                if (is_a($sub_entity, 'AVCMS\Core\Model\ExtensionEntity')) {

                    $sub_data = $sub_entity->toArray();
                    $sub_data_prefixed = array();

                    foreach ($sub_data as $param_name => $param_data) {
                        $sub_data_prefixed[$sub_entity->getPrefix().'__'.$param_name] = $param_data;
                    }

                    $data = array_merge($data, $sub_data_prefixed);
                }
            }

            return $data;
        }
    }

    /**
     * @param $name
     * @param Entity $entity
     * @param bool $extension
     */
    public function addSubEntity($name, Entity $entity, $extension = false)
    {
        $this->sub_entities[$name] = $entity;
    }

    /**
     * @param $value
     */
    public function setId($value) {
        $this->setData('id', $value);
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->data('id');
    }

    public function getAllSubEntities()
    {
        return $this->sub_entities;
    }

    public function getValidationRules(Validator $validator)
    {
        $this->validationRules($validator);

        foreach ($this->sub_entities as $sub_entity) {
            if (is_a($sub_entity, 'AVCMS\Core\Validation\Validatable')) {
                $validator->addSubValidation($sub_entity);
            }
        }
    }

    public function getValidationData()
    {
        return $this->toArray();
    }

    public function validationRules(Validator $validator)
    {

    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->sub_entities[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->sub_entities[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->sub_entities[$name]);
    }

    public function __clone()
    {
        foreach($this->sub_entities as $name => $obj) {
            $this->sub_entities[$name] = clone $obj;
        }
    }
}