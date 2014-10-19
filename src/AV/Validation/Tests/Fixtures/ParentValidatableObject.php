<?php
/**
 * User: Andy
 * Date: 07/12/2013
 * Time: 12:35
 */

namespace AV\Validation\Tests\Fixtures;

use AV\Validation\Validatable;
use AV\Validation\Validator;

class ParentValidatableObject implements Validatable {

    public function __construct()
    {
        $this->child_validatable = new ChildValidatableObject();
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('parent_parameter_one', new FailureRule(), "Parent Error 1"); // Error, FailureRule

        $validator->addRule('parent_parameter_two', new FailureRule(), "Parent Error 2", true);  // Success, allowed null

        $validator->addRule('parent_parameter_three', new SuccessRule(), "Parent Error 3"); // Success

        $validator->addRule('parent_parameter_four', new SuccessRule(), "Parent Error 4", true); // Success

        $validator->addRule('parent_parameter_five', new SuccessRule(), "Parent Error 5"); // Error, null not allowed

        $validator->addRule('shared_parameter_one', new FailureRule(), "Shared Param Error 1 - Parent"); // Error, FailureRule

        $validator->addRule('shared_parameter_two', new SuccessRule(), "Shared Param Error 2 - Parent"); // Success

        $validator->addRule('shared_parameter_three', new FailureRule(), "Shared Param Error 3 - Parent", false, true); // Error, FailureRule

        $validator->addRule('shared_parameter_four', new FailureRule(), "Shared Param Error 4 - Parent", false, true); // Error, not set

        $validator->addSubValidation($this->child_validatable);
    }


    public function getValidationData()
    {
        return array(
            'parent_parameter_one' => 'Parent String one',
            'parent_parameter_three' => 'Parent String three',
            'parent_parameter_four' => 'Parent String four',
            'shared_parameter_one' => 'Shared String One',
            'shared_parameter_two' => 'Shared String Two',
            'shared_parameter_three' => 'Shared String Three'
        );
    }

    public function expectedErrors($scope)
    {
        return array(
            'Parent Error 1',
            "Parameter '{param_name}' not set",
            'Shared Param Error 1 - Parent',
            'Shared Param Error 3 - Parent',
            "Parameter '{param_name}' not set"
        );
    }

    public function expectedValid()
    {
        return false;
    }

    public function getChildValidatable()
    {
        return $this->child_validatable;
    }
}