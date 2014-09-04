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
     * @var array Sections for grouping fields together for the view
     */
    protected $sections = array();

    /**
     * @var string A message to display when the form is submitted & valid
     */
    protected $successMessage;

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

            $explodedName = $this->explodeFieldNameArray($field['name']);

            // Unnamed array
            if (strpos($name, '[]') !== false) {
                $field['name'] = null;
                $explodedName[] = 'unnamed';
            }
            else {
                $field['name'] = end($explodedName);
            }

            $this->fields = $this->recursiveAddToFields($explodedName, $field);
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
     * @param $id
     * @param $label
     */
    public function addSection($id, $label)
    {
        $this->sections[$id] = array('label' => $label);
    }

    /**
     * @param $id
     */
    public function removeSection($id)
    {
        unset($this->sections[$id]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function hasSection($id)
    {
        return isset($this->sections[$id]);
    }

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    public function setSuccessMessage($message)
    {
        $this->successMessage = $message;
    }

    public function getSuccessMessage()
    {
        return (isset($this->successMessage) ? $this->successMessage : null);
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
    public function insertAfterKey(array $array, $key, $data = array(), $insertBefore = false)
    {
        if (($offset = array_search($key, array_keys($array))) === false) { // if the key doesn't exist
            $offset = count($array);
        }

        if ($insertBefore == false) {
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
     * @param $fieldName
     * @return array
     *
     * Example: $field_name = "example[one][two]"
     *          return array('example', 'one', 'two')
     */
    protected function explodeFieldNameArray($fieldName)
    {
        $name = str_replace('][', '.', $fieldName);
        $name = str_replace(array('[', ']'), '.', $name);

        $name = rtrim($name, '.');

        return explode('.', $name);
    }

    /**
     * @param array $exploded The exploded (explodeFieldNameArray) field name
     * @param array $field The new field's data
     * @param null $fieldsArray
     * @return array
     *
     * Recursively adds a field to the fields array.
     *
     * For example, will add a field "parent[sub_name]" as
     * $this->fields['parent']['fields']['sub_name']
     */
    protected  function recursiveAddToFields($exploded, $field, $fieldsArray = null) {
        if ($fieldsArray === null) {
            $fieldsArray = $this->fields;
        }

        $field_name = array_shift($exploded);

        if (!empty($exploded)) {
            if (!isset($fieldsArray[$field_name])) {
                $fieldsArray[$field_name] = array(
                    'name' => $field_name,
                    'type' => 'collection',
                    'fields' => array()
                );
            }
            $fieldsArray[$field_name]['fields'] = $this->recursiveAddToFields($exploded, $field, $fieldsArray[$field_name]['fields']);
        }
        else {
            if ($field_name == 'unnamed') {
                $fieldsArray[] = $field;
            }
            else {
                $fieldsArray[$field_name] = $field;
            }
        }

        return $fieldsArray;
    }
} 