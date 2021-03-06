<?php
/**
 * User: Andy
 * Date: 04/01/2014
 * Time: 15:46
 */

namespace AV\Model;

use AV\Validation\Validatable;
use AV\Validation\Validator;

/**
 * Class Entity
 * @package AV\Model
 */
abstract class Entity implements Validatable
{
    /**
     * @var array The data this entity is holding
     */
    protected $data = array();

    /**
     * @var array|Entity[] Any entities assigned via the magic __get method
     */
    protected $subEntities = array();

    /**
     * @param $param
     * @return null|string|int
     */
    protected function get($param)
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
    protected function set($param, $value)
    {
        $this->data[$param] = $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (empty($this->subEntities)) {
            return $this->data;
        }
        else {
            $data = $this->data;
            foreach ($this->subEntities as $subEntity) {
                if (is_a($subEntity, 'AV\Model\ExtensionEntity')) {

                    $sub_data = $subEntity->toArray();
                    $sub_data_prefixed = array();

                    foreach ($sub_data as $param_name => $param_data) {
                        $sub_data_prefixed[$subEntity->getPrefix().'__'.$param_name] = $param_data;
                    }

                    $data = array_merge($data, $sub_data_prefixed);
                }
            }

            return $data;
        }
    }

    public function fromArray(array $data, $ignoreUnusable = false, $ignoreKeys = [])
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $ignoreKeys)) {
                continue;
            }

            $key = str_replace('_', '', $key);

            if (!method_exists($this, 'set'.$key)) {
                if ($ignoreUnusable === false) {
                    throw new \Exception("This entity does not have a method for the data named $key");
                }
                else {
                    continue;
                }
            }

            $this->{'set'.$key}($value);
        }
    }

    /**
     * @param $name
     * @param Entity $entity
     */
    public function addSubEntity($name, Entity $entity)
    {
        $this->subEntities[$name] = $entity;
    }

    public function getAllSubEntities()
    {
        return $this->subEntities;
    }

    public function getValidationRules(Validator $validator)
    {
        $this->validationRules($validator);

        foreach ($this->subEntities as $subEntity) {
            if (is_a($subEntity, 'AV\Validation\Validatable')) {
                $validator->addSubValidation($subEntity);
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
        $this->subEntities[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->subEntities[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->subEntities[$name]);
    }

    public function __clone()
    {
        foreach($this->subEntities as $name => $obj) {
            if (is_object($obj)) {
                $this->subEntities[$name] = clone $obj;
            }
        }
    }
}
