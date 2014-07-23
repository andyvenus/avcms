<?php
/**
 * User: Andy
 * Date: 10/01/2014
 * Time: 16:17
 */

namespace AVCMS\Core\Form;


class FormBlueprint implements FormBlueprintInterface
{
    /**
     * @var array The form fields
     */
    protected $fields = array();

    /**
     * @var string The form method
     */
    protected $method = "POST";

    /**
     * @var null|string The form "action" parameter
     */
    protected $action = null;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * {@inheritdoc}
     */
    public function add($name, $type, $options = array())
    {
        $field = array(
            'name' => $name,
            'type' => $type,
            'options' => $options
        );

        if (strpos($name, '[') !== false) {
            preg_match('/\[([^\]]+)\]/', $name, $sub_field);

            $field['original_name'] = $field['name'];

            $exploded_name = $this->explodeFieldNameArray($field['name']);

            // Unnamed array
            if (strpos($name, '[]') !== false) {
                $field['name'] = null;
                $exploded_name[] = 'unnamed';
            }
            else {
                $field['name'] = end($exploded_name);
            }

            $this->fields = $this->recursiveAddToFields($exploded_name, $field);
        }
        else {

            if (isset($this->fields[$name])) {
                throw new \Exception("Can't add field, field '$name' already exists");
            }

            $this->fields[$name] = $field;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     * TODO: Make Recursive
     */
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

        return $this;
    }

    /**
     * {@inheritdoc}
     * TODO: Make Recursive
     */
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

        return $this;
    }

    /**
     * {@inheritdoc}
     */
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

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        unset($this->fields[$name]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return isset($this->fields[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }
        else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldNames()
    {
        return array_keys($this->fields);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultData($fields = null)
    {
        if (!$fields) {
            $fields = $this->fields;
        }

        $default_data = array();
        foreach ($fields as $field) {
            if (isset($field['options']['default'])) {
                if ($field['name'] === null) {
                    $default_data[] = $field['options']['default'];
                }
                else {
                    $default_data[$field['name']] = $field['options']['default'];
                }
            }
            elseif ($field['type'] == 'collection') {
                $default_data[$field['name']] = $this->getDefaultData($field['fields']);
            }
        }

        return $default_data;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function setAction($url)
    {
        $this->action = $url;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function insertAfterKey(array $array, $key, $data = array(), $insert_before = false)
    {
        if (($offset = array_search($key, array_keys($array))) === false) { // if the key doesn't exist
            $offset = count($array);
        }

        if ($insert_before == false) {
            $offset = $offset + 1;
        }

        return array_merge(array_slice($array, 0, $offset), (array) $data, array_slice($array, $offset));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $field_name
     * @return array
     *
     * Example: $field_name = "example[one][two]"
     *          return array('example', 'one', 'two')
     */
    protected function explodeFieldNameArray($field_name)
    {
        $name = str_replace('][', '.', $field_name);
        $name = str_replace(array('[', ']'), '.', $name);

        $name = rtrim($name, '.');

        return explode('.', $name);
    }

    /**
     * @param array $exploded The exploded (explodeFieldNameArray) field name
     * @param array $field The new field's data
     * @param null $fields_array
     * @return array
     *
     * Recursively adds a field to the fields array.
     *
     * For example, will add a field "parent[sub_name]" as
     * $this->fields['parent']['fields']['sub_name']
     */
    protected  function recursiveAddToFields($exploded, $field, $fields_array = null) {
        if ($fields_array === null) {
            $fields_array = $this->fields;
        }

        $field_name = array_shift($exploded);

        if (!empty($exploded)) {
            if (!isset($fields_array[$field_name])) {
                $fields_array[$field_name] = array(
                    'name' => $field_name,
                    'type' => 'collection',
                    'fields' => array()
                );
            }
            $fields_array[$field_name]['fields'] = $this->recursiveAddToFields($exploded, $field, $fields_array[$field_name]['fields']);
        }
        else {
            if ($field_name == 'unnamed') {
                $fields_array[] = $field;
            }
            else {
                $fields_array[$field_name] = $field;
            }
        }

        return $fields_array;
    }
} 