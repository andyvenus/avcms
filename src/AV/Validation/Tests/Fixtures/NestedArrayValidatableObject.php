<?php
/**
 * User: Andy
 * Date: 07/12/2013
 * Time: 12:35
 */

namespace AV\Validation\Tests\Fixtures;

use AV\Validation\Rules\Length;
use AV\Validation\Validatable;
use AV\Validation\Validator;

class NestedArrayValidatableObject implements Validatable {

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule(
            array ('parameter_two.inner_param', 'parameter_two.another_inner'),
            new Length(15), "Error 1"
        ); // Two errors

        $validator->addRule('parameter_one', new FailureRule(), "Error 2", true);  // One error

        $validator->addRule('parameter_three.inner_three.inner_inner', new Length(10), "Error 3", true);  // One error
    }

    public function getValidationData()
    {
        return array(
            'parameter_one' => 'String one',
            'parameter_two' => array(
                'inner_param' => 'Inner string',
                'another_inner' => 'Another inner that is long because of all these words'
            ),
            'parameter_three' => array(
                'inner_three' => array(
                    'inner_inner' => 'Short'
                )
            )
        );
    }

    public function expectedErrors()
    {
        return array(
            'Error 1',
            'Error 2',
            'Error 3'//,
        );
    }

    public function expectedValid()
    {
        return false;
    }
}