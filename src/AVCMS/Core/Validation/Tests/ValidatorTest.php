<?php

namespace AVCMS\Core\Validation\Tests;

use AVCMS\Core\Validation\Tests\Fixtures\ParentValidatableObject;
use AVCMS\Core\Validation\Tests\Fixtures\SimpleValidatableObject;
use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;

/**
 *  @group validator
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function tearDown()
    {
        $this->validator = null;
    }

    public function testSimpleValidatableObject()
    {
        $validatable = new SimpleValidatableObject();

        $this->validator->validate($validatable);

        $this->assertEquals($validatable->expectedValid(), $this->validator->isValid());

        if (!$validatable->expectedValid()) {

            $errors = $this->validator->getErrors();

            $error_messages = array();
            foreach ($errors as $error) {
                $error_messages[] = $error['error_message'];
            }

            $expected_errors = $validatable->expectedErrors();

            $this->assertEquals($expected_errors, $error_messages);
        }
    }

    /**
     * @dataProvider providerScopes
     */
    public function testSubvalidation($scope)
    {
        $parent_validatable = new ParentValidatableObject();
        $child_validatable = $parent_validatable->getChildValidatable();

        $this->validator->validate($parent_validatable, $scope);

        $this->assertEquals($parent_validatable->expectedValid(), $this->validator->isValid());

        if (!$parent_validatable->expectedValid()) {

            $errors = $this->validator->getErrors();

            $error_messages = array();
            foreach ($errors as $error) {
                $error_messages[] = $error['error_message'];
            }

            $expected_parent_errors = $parent_validatable->expectedErrors($scope);
            $expected_child_errors = $child_validatable->expectedErrors($scope);

            $expected_errors = array_merge($expected_parent_errors, $expected_child_errors);

            $this->assertEquals($expected_errors, $error_messages);
        }
    }

    public function providerScopes()
    {
        return array(
            array(Validator::SCOPE_ALL),
            array(Validator::SCOPE_PARENT_ONLY),
            array(Validator::SCOPE_SUB_SHARED)
        );
    }

    public function testTranslation()
    {
        $expected_errors[] = 'Error 1 Translated';
        $expected_errors[] = 'Error 1 Translated';
        $expected_errors[] = "Parameter 'parameter_five' not set Translated";

        $map = array(
            array('Error 1', array(), null, null, $expected_errors[0]),
            array("Parameter 'parameter_five' not set", array(), null, null, $expected_errors[2])
        );

        $mock_translator = $this->getMockBuilder('\AVCMS\Core\Translation\Translator')
            ->disableOriginalConstructor()
            ->getMock();

        $mock_translator->expects($this->any())
        ->method('trans')
        ->will($this->returnValueMap($map));

        $this->assertEquals('Error 1 Translated', $mock_translator->trans('Error 1'));

        $this->validator->setTranslator($mock_translator);

        $this->validator->validate(new SimpleValidatableObject());

        $errors = $this->validator->getErrors();

        $error_messages = array();

        foreach ($errors as $em) {
            $error_messages[] = $em['error_message'];
        }

        $this->assertEquals($expected_errors, $error_messages);
    }
}
 