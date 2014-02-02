<?php
/**
 * User: Andy
 * Date: 07/12/2013
 * Time: 12:35
 */

namespace AVCMS\Core\Validation\Tests\Fixtures;

use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;

class SimpleValidatableObject implements Validatable {

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule(array ('parameter_one', 'parameter_three'), new FailureRule(), "Error 1"); // Error, FailureRule

        $validator->addRule('parameter_two', new FailureRule(), "Error 2", true);  // Success, allowed null

        $validator->addRule('parameter_three', new SuccessRule(), "Error 3"); // Success

        $validator->addRule('parameter_four', new SuccessRule(), "Error 4", true); // Success

        $validator->addRule('parameter_five', new SuccessRule(), "Error 5"); // Error, null not allowed

        $validator->addRule('parameter_four', new FailureRule()); // Error, FailureRule. Use default error
    }

    public function getValidationData()
    {
        return array(
            'parameter_one' => 'String one',
            'parameter_three' => 'String three',
            'parameter_four' => 'String four'
        );
    }

    public function expectedErrors()
    {
        return array(
            'Error 1',
            'Error 1',
            "Parameter 'parameter_five' not set",
            "FailureRule default error on parameter_four"
        );
    }

    public function expectedValid()
    {
        return false;
    }
}