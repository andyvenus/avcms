<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 11:37
 */
namespace AVCMS\Core\Form\EntityProcessor;

interface EntityProcessor
{
    public function saveToEntity($entity, $form_data, $limit_fields = null);

    public function getFromEntity($entity, array $form_parameters, $limit_fields = null);
}