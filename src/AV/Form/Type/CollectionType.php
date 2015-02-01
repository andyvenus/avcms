<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:15
 */

namespace AV\Form\Type;

use AV\Form\FormHandler;

class CollectionType implements TypeInterface
{
    protected $typeHandler;

    public function __construct(TypeHandler $typeHandler)
    {
        $this->typeHandler = $typeHandler;
    }

    public function getDefaultOptions($field)
    {
        $fieldsUpdated = array();
        foreach ($field['fields'] as $fieldName => $innerField) {
            $fieldsUpdated[$fieldName] = $this->typeHandler->getDefaultOptions($innerField);
        }

        $field['fields'] = $fieldsUpdated;

        return $field;
    }

    public function allowUnsetRequest($field)
    {
        return (isset($field['options']['allow_unset']) && $field['options']['allow_unset'] === true); // technically will never be true
    }

    public function getUnsetRequestData($field)
    {
        return null;
    }

    public function isValidRequestData($field, $data)
    {
        return is_array($data);
    }

    public function processRequestData($field, $data)
    {
        return $data;
    }

    public function makeView($field, $data, FormHandler $formHandler)
    {
        if (isset($data[$field['name']])) {
            $field['value'] = $data[$field['name']];
        }

        $field['fields'] = $this->processFieldsCollection($field['fields'], (isset($field['value']) ? $field['value'] : null), $formHandler);

        return $field;
    }

    /**
     * Process a collection of fields
     *
     * @param $fieldCollection
     * @param $data
     * @param \AV\Form\FormHandler $formHandler
     * @return array
     */
    protected function processFieldsCollection($fieldCollection, $data, FormHandler $formHandler)
    {
        $fields = array();
        foreach ($fieldCollection as $field) {
            // Unnamed array fields
            if ($field['name'] === null) {
                if (!isset($i)) $i = 0;

                $field['name'] = $i;
                $field = $this->typeHandler->makeView($field, $data, $formHandler);
                $fields[] = $field;

                $i++;
            }
            // Named array fields
            else {
                $fieldName = $field['name'];
                $field = $this->typeHandler->makeView($field, $data, $formHandler);

                $field['has_error'] = false;
                if ($formHandler->fieldHasError($field['name'])) {
                    $field['has_error'] = true;
                }

                $fields[$fieldName] = $field;
            }
        }

        return $fields;
    }
}
