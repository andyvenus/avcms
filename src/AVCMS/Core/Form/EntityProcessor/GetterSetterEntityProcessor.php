<?php
/**
 * User: Andy
 * Date: 18/01/2014
 * Time: 18:09
 */

namespace AVCMS\Core\Form\EntityProcessor;


class GetterSetterEntityProcessor implements EntityProcessor
{
    public function getFromEntity($entity, array $form_parameters, $limit_fields = null)
    {
        $extracted_data = array();

        foreach($form_parameters as $field) {
            $getter_name = "get".$this->dashesToCamelCase($field);

            if (($limit_fields == null || in_array($field, $limit_fields)) && method_exists($entity, $getter_name) && ($value = $entity->$getter_name()) != null) {
                $extracted_data[$field] = $value;
            }
        }

        return $extracted_data;
    }

    public function saveToEntity($entity, $form_data, $limit_fields = null)
    {
        foreach($form_data as $field => $value) {
            $setter_name = "set".$this->dashesToCamelCase($field);

            if (($limit_fields == null || in_array($field, $limit_fields)) && method_exists($entity, $setter_name)) {
                $entity->$setter_name($value);
            }
        }
    }

    protected function dashesToCamelCase($string)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        return $str;
    }
} 