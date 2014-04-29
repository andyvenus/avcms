<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:15
 */

namespace AVCMS\Core\Form\Type;

use AVCMS\Core\Form\FormHandler;

class TypeHandler implements TypeInterface
{
    public function __construct()
    {
        $this->types = array(
            'checkbox' => new CheckboxType(),
            'collection' => new CollectionType($this),
            'default' => new DefaultType()
        );
    }

    /**
     * @param $field_type
     * @return TypeInterface
     */
    public function getType($field_type)
    {
        if (isset($this->types[$field_type])) {
            return $this->types[$field_type];
        }
        else {
            return $this->types['default'];
        }
    }

    public function addType($id, TypeInterface $type)
    {
        $this->types[$id] = $type;
    }

    public function getDefaultOptions($field)
    {
        $type = $this->getType($field['type']);

        return $type->getDefaultOptions($field);
    }

    public function isValidRequestData($field, $data)
    {
        $type = $this->getType($field['type']);

        return $type->isValidRequestData($field, $data);
    }

    public function allowUnsetRequest($field)
    {
        $type = $this->getType($field['type']);

        return $type->allowUnsetRequest($field);
    }

    public function processRequestData($field, $data)
    {
        $type = $this->getType($field['type']);

        return $type->processRequestData($field, $data);
    }

    public function getUnsetRequestData($field)
    {
        $type = $this->getType($field['type']);

        return $type->getUnsetRequestData($field);
    }

    public function makeView($field, $all_form_data, FormHandler $form_handler)
    {
        $type = $this->getType($field['type']);

        return $type->makeView($field, $all_form_data, $form_handler);
    }
} 