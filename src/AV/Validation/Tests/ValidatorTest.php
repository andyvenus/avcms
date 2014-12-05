<?php

namespace AV\Validation\Tests;

use AV\Validation\Rules\Length;
use AV\Validation\Tests\Fixtures\NestedArrayValidatableObject;
use AV\Validation\Tests\Fixtures\ParentValidatableObject;
use AV\Validation\Tests\Fixtures\SimpleValidatableObject;
use AV\Validation\Validator;

/**
 *  @group validator
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{

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

    public function testNestedArrayValidatableObject()
    {
        $validatable = new NestedArrayValidatableObject();

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

        $this->validator->setEventDispatcher($this->getMock('Symfony\Component\EventDispatcher\EventDispatcher'));

        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $translator->expects($this->any())
            ->method('trans')
            ->will($this->returnArgument(0));

        $this->validator->setTranslator($translator);

        $this->validator->validate($parent_validatable, 'standard', $scope);

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

    public function testArrayValidation()
    {
        $this->validator->addRule('name', new Length(50));

        $this->validator->validate(array(
            'name' => 'Too Short'
        ));

        $this->assertFalse($this->validator->isValid());
        $this->assertCount(1, $this->validator->getErrors());
    }

    public function testInvalidHandlerException()
    {
        $this->setExpectedException('\Exception');

        $this->validator->validate(new SimpleValidatableObject(), 'invalid');
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
        $expected_errors[] = "FailureRule default error on {param_name} Translated";

        $map = array(
            array('Error 1', array('param_name' => 'parameter_one'), null, null, $expected_errors[0]),
            array('Error 1', array('param_name' => 'parameter_three'), null, null, $expected_errors[1]),
            array("Parameter '{param_name}' not set", array('param_name' => 'parameter_five'), null, null, $expected_errors[2]),
            array("FailureRule default error on {param_name}", array('param_name' => 'parameter_four'), null, null, $expected_errors[3])
        );

        $mock_translator = $this->getMockBuilder('\AVCMS\Core\Translation\Translator')
            ->disableOriginalConstructor()
            ->getMock();

        $mock_translator->expects($this->any())
        ->method('trans')
        ->will($this->returnValueMap($map));

        $this->validator->setTranslator($mock_translator);

        $this->validator->validate(new SimpleValidatableObject());

        $errors = $this->validator->getErrors();

        $error_messages = array();

        foreach ($errors as $em) {
            $error_messages[] = $em['error_message'];
        }

        $this->assertEquals($expected_errors, $error_messages);
    }

    public function testEvents()
    {
        $mock_dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');

        $mock_dispatcher->expects($this->any())
            ->method('dispatch');

        $this->validator->setEventDispatcher($mock_dispatcher);

        $this->validator->validate(new SimpleValidatableObject());
    }
}
 