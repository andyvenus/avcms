<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:15
 */

namespace AVCMS\Core\Form\Type;

class TypeHandler implements TypeInterface
{
    public function __construct()
    {
        $this->types = array(
            'text' => new BaseType()
        );
    }

    /**
     * @param $field_type
     * @return TypeInterface
     */
    public function getType($field_type)
    {
        return $this->types[$field_type];
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
} 