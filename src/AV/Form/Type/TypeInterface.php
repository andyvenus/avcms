<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:17
 */
namespace AV\Form\Type;

use AV\Form\FormHandler;

interface TypeInterface
{
    public function getDefaultOptions($field);

    public function allowUnsetRequest($field);

    public function getUnsetRequestData($field);

    public function isValidRequestData($field, $data);

    public function processRequestData($field, $data);

    public function makeView($field, $data, FormHandler $formHandler);
}