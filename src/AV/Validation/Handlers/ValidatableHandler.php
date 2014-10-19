<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 17:57
 */
namespace AV\Validation\Handlers;

use AV\Validation\Validator;

interface ValidatableHandler
{
    public function getValidationRules($validatable, Validator $validator);

    public function getValidationData($validatable, Validator $validator);

    public function getName();
}