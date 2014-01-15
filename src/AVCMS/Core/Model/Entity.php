<?php
/**
 * User: Andy
 * Date: 04/01/2014
 * Time: 15:46
 */

namespace AVCMS\Core\Model;


/**
 * Class Entity
 * @package AVCMS\Core\Model
 */
class Entity
{
    /**
     * @var array The data this entity is holding
     */
    protected $data = array();

    /**
     * @var array Any entities assigned via the magic __get method
     */
    protected $sub_entities;

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
     * @depreciated
     */
    public function getData()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param $name
     * @param Entity $entity
     */
    public function addSubEntity($name, Entity $entity)
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

    // Magic methods to get sub-entities as params like
    // $entity->category->getName();
}