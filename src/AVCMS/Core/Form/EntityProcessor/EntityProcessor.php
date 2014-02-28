<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 11:37
 */
namespace AVCMS\Core\Form\EntityProcessor;

/**
 * Interface EntityProcessor
 * @package AVCMS\Core\Form\EntityProcessor
 */
interface EntityProcessor
{
    /**
     * Save an array of form data to an entity
     *
     * @param $entity
     * @param $form_data
     * @param null $limit_fields
     * @return mixed
     */
    public function saveToEntity($entity, $form_data, $limit_fields = null);

    /**
     * Get an array of data from an entity
     *
     * @param $entity
     * @param array $form_parameters
     * @param null $limit_fields
     * @return mixed
     */
    public function getFromEntity($entity, array $form_parameters, $limit_fields = null);
}