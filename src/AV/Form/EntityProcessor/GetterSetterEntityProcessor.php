<?php
/**
 * User: Andy
 * Date: 18/01/2014
 * Time: 18:09
 */

namespace AV\Form\EntityProcessor;


/**
 * Class GetterSetterEntityProcessor
 * @package AV\Form\EntityProcessor
 */
class GetterSetterEntityProcessor implements EntityProcessorInterface
{
    /**
     * Get data from an entity using getter methods
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

            if (($limitFields === null || in_array($field, $limitFields)) && method_exists($entity, $getter_name) && ($value = $entity->$getter_name()) !== null) {
                $extracted_data[$field] = $value;
            }
        }

        return $extracted_data;
    }

    /**
     * Save data to an entity using setter methods
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

            if (($limitFields === null || in_array($field, $limitFields)) && method_exists($entity, $setter_name)) {
                $entity->$setter_name($value);
            }
        }
    }

    /**
     * Convert string from something_like_this to somethingLikeThis
     *
     * @param $string
     * @return mixed
     */
    protected function dashesToCamelCase($string)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        return $str;
    }
} 