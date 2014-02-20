<?php
/**
 * User: Andy
 * Date: 19/02/2014
 * Time: 15:30
 */

namespace AVCMS\Core\Model;

use AVCMS\Core\Form\EntityProcessor\EntityProcessor;

class FormEntityProcessor implements EntityProcessor
{
    public function getFromEntity($entity, array $form_parameters, $limit_fields = null)
    {
        $extracted_data = array();

        foreach($form_parameters as $field) {
            $getter_name = "get".$this->dashesToCamelCase($field);

            if (($limit_fields == null || in_array($field, $limit_fields))) {
                if (method_exists($entity, $getter_name) && ($value = $entity->$getter_name()) !== null) {
                    $extracted_data[$field] = $value;
                }
                else {
                    $sub_entities = $entity->getAllSubEntities();

                    foreach ($sub_entities as $sub_entity) {
                        if (!empty($sub_entities) && method_exists($sub_entity, $getter_name) && ($value = $sub_entity->$getter_name()) !== null) {
                            $extracted_data[$field] = $value;
                        }
                    }
                }
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

        foreach($form_data as $field => $value) {
            $setter_name = "set".$this->dashesToCamelCase($field);

            if (($limit_fields == null || in_array($field, $limit_fields))) {
                if (method_exists($entity, $setter_name)) {
                    $entity->$setter_name($value);
                }
                else {
                    $sub_entities = $entity->getAllSubEntities();

                    foreach ($sub_entities as $sub_entity) {
                        if (!empty($sub_entities) && method_exists($sub_entity, $setter_name)) {
                            $sub_entity->$setter_name($value);
                        }
                    }
                }
            }
        }
    }

    protected function dashesToCamelCase($string)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        return $str;
    }
}