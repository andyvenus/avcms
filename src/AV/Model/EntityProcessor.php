<?php
/**
 * User: Andy
 * Date: 19/02/2014
 * Time: 15:30
 */

namespace AV\Model;

use AV\Form\EntityProcessor\EntityProcessorInterface as FormEntityProcessor;

/**
 * Class EntityProcessor
 * @package AV\Model
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
        $extractedData = array();

        foreach($formParameters as $field) {
            $getterName = "get".$this->dashesToCamelCase($field);

            if (($limitFields === null || in_array($field, $limitFields))) {
                if (is_callable(array($entity, $getterName)) && ($value = $entity->$getterName()) !== null) {
                    $extractedData[$field] = $value;
                }
                else {
                    $subEntities = $entity->getAllSubEntities();

                    foreach ($subEntities as $subEntity) {
                        if (!empty($subEntities) && is_callable(array($subEntity, $getterName)) && ($value = $subEntity->$getterName()) !== null) {
                            $extractedData[$field] = $value;
                        }
                    }
                }
            }
        }

        return $extractedData;
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
            $setterName = "set".$this->dashesToCamelCase($field);

            if (($limitFields === null || in_array($field, $limitFields))) {
                if (is_callable(array($entity, $setterName))) {
                    $entity->$setterName($value);
                }
                else {
                    $sub_entities = $entity->getAllSubEntities();

                    foreach ($sub_entities as $subEntity) {
                        if (!empty($sub_entities) && is_callable(array($subEntity, $setterName))) {
                            $subEntity->$setterName($value);
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