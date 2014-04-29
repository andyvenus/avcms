<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:17
 */
namespace AVCMS\Core\Form\Type;

use AVCMS\Core\Form\FormHandler;

interface TypeInterface
{
    public function getDefaultOptions($field);

    public function allowUnsetRequest($field);

    public function getUnsetRequestData($field);

    public function isValidRequestData($field, $data);

    public function processRequestData($field, $data);

    public function makeView($field, $data, FormHandler $form_handler);
}