<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:15
 */

namespace AVCMS\Core\Form\Type;

class BaseType implements TypeInterface
{
    public function allowUnsetRequest($field)
    {
        return (isset($field['options']['allow_unset']) && $field['options']['allow_unset'] === true);
    }

    public function getUnsetRequestData($field)
    {
        return null;
    }

    public function isValidRequestData($field, $data)
    {
        if ($data !== null) {
            return true;
        }
    }

    public function processRequestData($field, $data)
    {
        return $data;
    }
}