<?php
/**
 * User: Andy
 * Date: 07/12/2013
 * Time: 12:35
 */

namespace AVCMS\Core\Validation\Tests\Fixtures;

use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;

class ValidatableObject implements Validatable {

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('parameter_one', new FailureRule(), "Error 1");

        $validator->addRule('parameter_two', new SuccessRule(), "Error 2");

        $validator->addRule('parameter_three', new SuccessRule(), "Error 3");

        $validator->addRule('parameter_four', new SuccessRule(), "Error 4", true);
    }

    public function getParameters()
    {
        return array(
            'parameter_one' => 'String one',
            'parameter_two' => 'String two'
        );
    }

    public function expectedErrors()
    {
        return array(
            'Error 1',
            "Parameter 'parameter_three' not set"
        );
    }

    public function expectedValid()
    {
        return false;
    }
}