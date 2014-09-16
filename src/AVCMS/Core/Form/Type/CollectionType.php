<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:15
 */

namespace AVCMS\Core\Form\Type;

use AVCMS\Core\Form\FormHandler;

class CollectionType implements TypeInterface
{
    public function __construct(TypeHandler $type_handler)
    {
        $this->type_handler = $type_handler;
    }

    public function getDefaultOptions($field)
    {
        $fields_updated = array();
        foreach ($field['fields'] as $field_name => $inner_field) {
            $fields_updated[$field_name] = $this->type_handler->getDefaultOptions($inner_field);
        }

        $field['fields'] = $fields_updated;

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
     * @param $field_collection
     * @param $data
     * @param \AVCMS\Core\Form\FormHandler $form_handler
     * @return array
     */
    protected function processFieldsCollection($field_collection, $data, FormHandler $form_handler)
    {
        $fields = array();
        foreach ($field_collection as $field) {
            // Unnamed array fields
            if ($field['name'] === null) {
                if (!isset($i)) $i = 0;

                $field['name'] = $i;
                $field = $this->type_handler->makeView($field, $data, $form_handler);
                $fields[] = $field;

                $i++;
            }
            // Named array fields
            else {
                $field_name = $field['name'];
                $field = $this->type_handler->makeView($field, $data, $form_handler);

                $field['has_error'] = false;
                if ($form_handler->fieldHasError($field['name'])) {
                    $field['has_error'] = true;
                }

                $fields[$field_name] = $field;
            }
        }

        return $fields;
    }
}