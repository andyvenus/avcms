<?php
/**
 * User: Andy
 * Date: 10/01/2014
 * Time: 16:17
 */

namespace AV\Form;

/**
 * Class FormBlueprint
 * @package AV\Form
 *
 * Create a blueprint of a form that can be built and handled by the FormHandler
 */
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

        if (strpos($name, '[') !== false && (isset($options['attr']['multiple']) || isset($options['accept_array']))) {
            $field['original_name'] = $field['name'];
            $field['name'] = str_replace('[]', '', $field['name']);
            $field['options']['allow_unset'] = true;
            if (!isset($field['options']['unset_value'])) {
                $field['options']['unset_value'] = null;
            }

            $this->fields[$field['name']] = $field;
        }
        elseif (strpos($name, '[') !== false) {
            preg_match('/\[([^\]]+)\]/', $name, $subField);

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
     */
    public function addBefore($offset, $name, $type, $options = array())
    {
        $newField = array(
            $name => array(
                'name' => $name,
                'type' => $type,
                'options' => $options
            )
        );

        $this->fields = $this->insertAfterKey($this->fields, $offset, $newField, true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addAfter($offset, $name, $type, $options = array())
    {
        $newField = array(
            $name => array(
                'name' => $name,
                'type' => $type,
                'options' => $options
            )
        );

        $this->fields = $this->insertAfterKey($this->fields, $offset, $newField);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function replace($name, $type, $options = array(), $addIfNotExist = false)
    {
        if (!isset($this->fields[$name]) && $addIfNotExist === false) {
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

        $defaultData = array();
        foreach ($fields as $field) {
            if (isset($field['options']['default'])) {
                if ($field['name'] === null) {
                    $defaultData[] = $field['options']['default'];
                }
                else {
                    $defaultData[$field['name']] = $field['options']['default'];
                }
            }
            elseif ($field['type'] == 'collection') {
                $defaultData[$field['name']] = $this->getDefaultData($field['fields']);
            }
        }

        return $defaultData;
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

        if ($insertBefore === false) {
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
     * @param array $fields
     * @param $group null|string Add a prefix to all field names
     * @param null $section
     * @throws \Exception
     */
    public function createFieldsFromArray(array $fields, $group = null, $section = null)
    {
        foreach ($fields as $field_name => $field) {
            $field_type = (isset($field['type']) ? $field['type'] : 'text');
            unset($field['type']);

            if (!isset($field['section']) && $section === null) {
                $field['section'] = 'main';
            }
            elseif (!isset($field['section'])) {
                $field['section'] = $section;
            }

            if ($group) {
                $field_name = $group.'['.$field_name.']';
            }

            $this->add($field_name, $field_type, $field);
        }
    }

    /**
     * @param array $sections
     */
    public function createSectionsFromArray(array $sections)
    {
        foreach ($sections as $id => $section) {
            $this->addSection($id, $section['label']);
        }
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
    protected function recursiveAddToFields($exploded, $field, $fieldsArray = null) {
        if ($fieldsArray === null) {
            $fieldsArray = $this->fields;
        }

        $fieldName = array_shift($exploded);

        if (!empty($exploded)) {
            if (!isset($fieldsArray[$fieldName])) {
                $fieldsArray[$fieldName] = array(
                    'name' => $fieldName,
                    'type' => 'collection',
                    'fields' => array()
                );
            }
            $fieldsArray[$fieldName]['fields'] = $this->recursiveAddToFields($exploded, $field, $fieldsArray[$fieldName]['fields']);
        }
        else {
            if ($fieldName == 'unnamed') {
                $fieldsArray[] = $field;
            }
            else {
                $fieldsArray[$fieldName] = $field;
            }
        }

        return $fieldsArray;
    }
} 