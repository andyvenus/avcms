<?php
/**
 * User: Andy
 * Date: 21/01/2014
 * Time: 18:53
 */

namespace AVCMS\Core\Validation\Handlers;

use AVCMS\Core\Validation\Validator;

/**
 * Class ArrayValidatableHandler
 * @package AVCMS\Core\Validation\Handlers
 *
 * Simply returns the array that was fed in
 */
class ArrayValidatableHandler implements ValidatableHandler {

    /**
     * @param $validatable
     * @param Validator $validator
     * @return null
     */
    public function getValidationRules($validatable, Validator $validator)
    {
        return null;
    }

    /**
     * @param $validatable
     * @param Validator $validator
     * @return mixed
     */
    public function getValidationData($validatable, Validator $validator)
    {
        return $validatable;
    }
}