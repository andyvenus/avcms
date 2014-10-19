<?php
/**
 * User: Andy
 * Date: 28/03/2014
 * Time: 16:19
 */

namespace AV\Form\Tests\Type;

use AV\Form\Type\CheckboxType;

class CheckboxTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CheckboxType
     */
    private $default_type;

    /**
     * @var array
     */
    private $basic_field;

    public function setUp()
    {
        $this->default_type = new CheckboxType();
        $this->basic_field = array(
            'name' => 'basic_field',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Basic Field',
                'allow_unset' => false,
                'checked' => false,
                'checked_value' => 1,
                'unchecked_value' => 0
            ),
            'original_name' => 'basic_field_original'
        );
    }

    public function testGetDefaultOptions()
    {
        $field = $this->basic_field;
        unset($field['options']['checked']);
        unset($field['options']['checked_value']);
        unset($field['options']['unchecked_value']);

        $field_processed = $this->default_type->getDefaultOptions($this->basic_field);

        $this->assertTrue(isset($field_processed['options']['checked']));
        $this->assertTrue(isset($field_processed['options']['checked_value']));
        $this->assertTrue(isset($field_processed['options']['unchecked_value']));
    }

    public function testAllowUnsetRequest()
    {
        $this->assertTrue($this->default_type->allowUnsetRequest(array()));
    }

    public function testGetUnsetRequestData()
    {
        $this->assertEquals(null, $this->default_type->getUnsetRequestData($this->basic_field));
    }

    public function testIsValidRequestData()
    {
        $this->assertFalse($this->default_type->isValidRequestData($this->basic_field, null));
        $this->assertFalse($this->default_type->isValidRequestData($this->basic_field, '0'));
        $this->assertTrue($this->default_type->isValidRequestData($this->basic_field, '1'));
    }

    public function testProcessRequestData()
    {
        // For a checkbox, it doesn't matter what the data is. No matter what it's set to, the processRequestData method will
        // return the value of 'checked_value'
        $data = '123';

        $processed = $this->default_type->processRequestData($this->basic_field, $data);

        $this->assertEquals('1', $processed);
    }

    public function testMakeView()
    {
        $form_handler = $this->getMockBuilder('\AV\Form\FormHandler')
        ->disableOriginalConstructor()
        ->getMock();

        $form_handler->expects($this->any())
            ->method('isSubmitted')
            ->will($this->returnValue(true));

        $unchecked_view = $this->default_type->makeView($this->basic_field, array('basic_field' => 'invalid'), $form_handler);

        $this->assertEquals(1, $unchecked_view['value']);
        $this->assertFalse($unchecked_view['options']['checked']);

        $checked_view = $this->default_type->makeView($this->basic_field, array('basic_field' => '1'), $form_handler);

        $this->assertTrue($checked_view['options']['checked']);
        $this->assertEquals('basic_field_original', $checked_view['name']);
    }
}
 