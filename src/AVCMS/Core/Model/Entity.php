<?php
/**
 * User: Andy
 * Date: 04/01/2014
 * Time: 15:46
 */

namespace AVCMS\Core\Model;


class Entity
{
    protected $data = array();

    protected $sub_entities;

    protected function data($param)
    {
        if (isset($this->data[$param])) {
            return $this->data[$param];
        }
        else {
            return null;
        }
    }

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

    public function toArray()
    {
        return $this->data;
    }

    public function addSubEntity($name, Entity $entity)
    {
        $this->sub_entities[$name] = $entity;
    }

    public function setId($value) {
        $this->setData('id', $value);
    }

    public function getId() {
        return $this->data('id');
    }

    public function __set($name, $value)
    {
        $this->sub_entities[$name] = $value;
    }

    public function __get($name)
    {
        return $this->sub_entities[$name];
    }

    public function __isset($name)
    {
        return isset($this->sub_entities[$name]);
    }

    // Magic methods to get sub-entities as params like
    // $entity->category->getName();
}