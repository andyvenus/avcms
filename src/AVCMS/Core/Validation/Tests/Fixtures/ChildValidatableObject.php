<?php
/**
 * User: Andy
 * Date: 07/12/2013
 * Time: 12:35
 */

namespace AVCMS\Core\Validation\Tests\Fixtures;

use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;

class ChildValidatableObject implements Validatable {

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('child_parameter_one', new FailureRule(), "Child Error 1"); // Error, FailureRule

        $validator->addRule('child_parameter_two', new FailureRule(), "Child Error 2", true);  // Success, allowed null

        $validator->addRule('child_parameter_three', new SuccessRule(), "Child Error 3"); // Success

        $validator->addRule('child_parameter_four', new SuccessRule(), "Child Error 4", true); // Success

        $validator->addRule('child_parameter_five', new SuccessRule(), "Child Error 5"); // Error, null not allowed

        $validator->addRule('shared_parameter_one', new FailureRule(), "Shared Param Error 1 - Child"); // Error, FailureRule

        // Errors but should not be reached due to parent throwing propagation-stopping error
        $validator->addRule('shared_parameter_three', new FailureRule(), "Shared Param Error 3 - Child");

        $validator->addRule('shared_parameter_four', new FailureRule(), "Shared Param Error 4 - Child");

    }

    public function getValidationData()
    {
        return array(
            'child_parameter_one' => 'Child String one',
            'child_parameter_three' => 'Child String three',
            'child_parameter_four' => 'Child String four',
            'shared_parameter_one' => 'Shared String One',
            'shared_parameter_two' => 'Shared String Two',
            'shared_parameter_three' => 'Shared String Three'
        );
    }

    public function expectedErrors($scope)
    {
        if ($scope == Validator::SCOPE_ALL) {
            return array(
                'Child Error 1',
                "Parameter 'child_parameter_five' not set",
                'Shared Param Error 1 - Child'
            );
        }
        elseif ($scope == Validator::SCOPE_SUB_SHARED) {
            return array(
                'Shared Param Error 1 - Child',
            );
        }
        elseif ($scope == Validator::SCOPE_PARENT_ONLY) {
            return array();
        }
    }

    public function expectedValid()
    {
        return false;
    }
}