<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:17
 */
namespace AVCMS\Core\Form\Type;

interface TypeInterface
{
    public function allowUnsetRequest($field);

    public function getUnsetRequestData($field);

    public function isValidRequestData($field, $data);

    public function processRequestData($field, $data);
}