<?php
/**
 * User: Andy
 * Date: 19/02/2014
 * Time: 15:30
 */

namespace AVCMS\Core\Model;

use AV\Form\EntityProcessor\EntityProcessor as FormEntityProcessor;

/**
 * Class EntityProcessor
 * @package AVCMS\Core\Model
 */
class EntityProcessor implements FormEntityProcessor
{
    /**
     * Get data from an entity using getter methods. Check for 'sub-entities' and merge their data.
     *
     * @param $entity
     * @param array $formParameters
     * @param null $limitFields
     * @return array
     */
    public function getFromEntity($entity, array $formParameters, $limitFields = null)
    {
        $extracted_data = array();

        foreach($formParameters as $field) {
            $getter_name = "get".$this->dashesToCamelCase($field);

            if (($limitFields == null || in_array($field, $limitFields))) {
                if (is_callable(array($entity, $getter_name)) && ($value = $entity->$getter_name()) !== null) {
                    $extracted_data[$field] = $value;
                }
                else {
                    $sub_entities = $entity->getAllSubEntities();

                    foreach ($sub_entities as $sub_entity) {
                        if (!empty($sub_entities) && is_callable(array($sub_entity, $getter_name)) && ($value = $sub_entity->$getter_name()) !== null) {
                            $extracted_data[$field] = $value;
                        }
                    }
                }
            }
        }

        return $extracted_data;
    }

    /**
     * Save the form data to an entity and any sub-entities if they are set
     *
     * @param $entity
     * @param $formData
     * @param null $limitFields
     * @return void
     */
    public function saveToEntity($entity, $formData, $limitFields = null)
    {
        foreach($formData as $field => $value) {
            $setter_name = "set".$this->dashesToCamelCase($field);

            if (($limitFields == null || in_array($field, $limitFields))) {
                if (is_callable(array($entity, $setter_name))) {
                    $entity->$setter_name($value);
                }
                else {
                    $sub_entities = $entity->getAllSubEntities();

                    foreach ($sub_entities as $sub_entity) {
                        if (!empty($sub_entities) && is_callable(array($sub_entity, $setter_name))) {
                            $sub_entity->$setter_name($value);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $string
     * @return mixed
     */
    protected function dashesToCamelCase($string)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        return $str;
    }
}