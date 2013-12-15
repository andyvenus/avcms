<?php

namespace AVCMS\Validation\Tests;

use AVCMS\Validation\Rules\MinLength;
use AVCMS\Validation\Tests\Fixtures\ValidatableObject;
use AVCMS\Validation\Validator;

/**
 *  @group validator
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @param $validatable
     * @dataProvider providerValidatables
     */
    public function testValidateForm($validatable)
    {
        $validator = new Validator();

        $validator->validate($validatable);

        $this->assertEquals($validatable->expectedValid(), $validator->isValid());

        $errors = $validator->getErrors();

        $error_messages = array();
        foreach ($errors as $error) {
            $error_messages[] = $error['error_message'];
        }

        $expected_errors = $validatable->expectedErrors();

        $this->assertEquals($expected_errors, $error_messages);
    }

    public function providerValidatables()
    {
        return array(
            array (new ValidatableObject())
        );
    }

}
 