<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 10:30
 */

namespace AV\Validation\Handlers;

use AV\Validation\Validator;

class SelfValidatableHandler implements ValidatableHandler
{
    public function getValidationRules($validatable, Validator $validator)
    {
        if (!method_exists($validatable, 'getValidationRules')) {
            $name = get_class($validatable);
            throw new \Exception("Object $name does not have a getValidationRules method");
        }

        return $validatable->getValidationRules($validator);
    }

    public function getValidationData($validatable, Validator $validator)
    {
        if (!method_exists($validatable, 'getValidationData')) {
            $name = get_class($validatable);
            throw new \Exception("Object $name does not have a getValidationData method");
        }

        return $validatable->getValidationData();
    }

    public function getName()
    {
        return 'standard';
    }
}