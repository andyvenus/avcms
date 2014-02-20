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
abstract class Entity
{
    /**
     * @var array The data this entity is holding
     */
    protected $data = array();

    /**
     * @var array Any entities assigned via the magic __get method
     */
    protected $sub_entities = array();

    /**
     * @var array
     */
    protected $extension_sub_entities = array();

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
        if (empty($this->sub_entities)) {
            return $this->data;
        }
        else {
            $data = $this->data;
            foreach ($this->sub_entities as $sub_entity) {
                if (is_a($sub_entity, 'AVCMS\Core\Model\ExtensionEntity')) {

                    $sub_data = $sub_entity->toArray();

                    $data = array_merge($data, $sub_data);
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

        if ($extension) {
            $this->extension_sub_entities[] = $name;
        }
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