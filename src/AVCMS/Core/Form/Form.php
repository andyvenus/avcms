<?php
/**
 * User: Andy
 * Date: 10/01/2014
 * Time: 16:17
 */

namespace AVCMS\Core\Form;


class Form
{
    protected $fields = array();

    public function add($name, $type, $options = array())
    {

        if (isset($this->fields[$name])) {
            throw new \Exception("Can't add field, field '$name' already exists");
        }

        $this->fields[$name] = array(
            'name' => $name,
            'type' => $type,
            'options' => $options
        );
    }

    public function replace($name, $type, $options = array(), $add_if_not_exist = false)
    {
        if (!isset($this->fields[$name]) && $add_if_not_exist == false) {
            throw new \Exception("Can't replace field, field '$name' doesn't exist");
        }

        $this->fields[$name] = array(
            'name' => $name,
            'type' => $type,
            'options' => $options
        );
    }

    public function addAfter($offset, $name, $type, $options = array())
    {
        $new_field = array(
            $name => array(
                'name' => $name,
                'type' => $type,
                'options' => $options
            )
        );

        $this->fields = $this->insertAfterKey($this->fields, $offset, $new_field);
    }

    public function addBefore($offset, $name, $type, $options = array())
    {
        $new_field = array(
            $name => array(
                'name' => $name,
                'type' => $type,
                'options' => $options
            )
        );

        $this->fields = $this->insertAfterKey($this->fields, $offset, $new_field, true);
    }

    public function remove($name)
    {
        unset($this->fields[$name]);
    }

    public function has($name)
    {
        return isset($this->fields[$name]);
    }

    public function get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }
        else {
            return null;
        }
    }

    public function getFields()
    {
        return array_keys($this->fields);
    }

    public function getAll()
    {
        return $this->fields;
    }

    protected function insertAfterKey($array, $key, $data = null, $insert_before = false)
    {
        if (($offset = array_search($key, array_keys($array))) === false) { // if the key doesn't exist
            $offset = count($array);
        }

        if ($insert_before == false) {
            $offset = $offset + 1;
        }

        return array_merge(array_slice($array, 0, $offset), (array) $data, array_slice($array, $offset));
    }
} 