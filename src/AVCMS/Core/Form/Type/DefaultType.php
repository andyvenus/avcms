<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:15
 */

namespace AVCMS\Core\Form\Type;

use AVCMS\Core\Form\FormHandler;

class DefaultType implements TypeInterface
{
    public function getDefaultOptions($field)
    {
        return $field;
    }

    public function allowUnsetRequest($field)
    {
        return (isset($field['options']['allow_unset']) && $field['options']['allow_unset'] === true);
    }

    public function getUnsetRequestData($field)
    {
        if (isset($field['options']['unset_value'])) {
            return $field['options']['unset_value'];
        }

        return null;
    }

    public function isValidRequestData($field, $data)
    {
        if ($data !== null) {
            return true;
        }
        else {
            return false;
        }
    }

    public function processRequestData($field, $data)
    {
        return $data;
    }

    public function makeView($field, $all_form_data, FormHandler $form_handler)
    {
        if (isset($all_form_data[$field['name']])) {
            $field['value'] = $all_form_data[$field['name']];
        }

        if (isset($field['original_name'])) {
            $field['name'] = $field['original_name'];
        }

        return $field;
    }
}