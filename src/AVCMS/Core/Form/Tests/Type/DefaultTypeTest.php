<?php
/**
 * User: Andy
 * Date: 28/03/2014
 * Time: 16:19
 */

namespace AVCMS\Core\Form\Tests\Type;


use AVCMS\Core\Form\Type\DefaultType;

class DefaultTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultType
     */
    private $default_type;

    /**
     * @var array
     */
    private $basic_field;

    public function setUp()
    {
        $this->default_type = new DefaultType();
        $this->basic_field = array(
            'name' => 'basic_field',
            'type' => 'text',
            'options' => array(
                'label' => 'Basic Field',
                'allow_unset' => false
            )
        );
    }

    public function testGetDefaultOptions()
    {
        $field = $this->default_type->getDefaultOptions($this->basic_field);

        $this->assertEquals($this->basic_field, $field);
    }

    /**
     * @dataProvider providerAllowUnsetRequest
     */
    public function testAllowUnsetRequest($field, $allowed_unset)
    {
        $this->assertEquals($allowed_unset, $this->default_type->allowUnsetRequest($field));
    }

    public function providerAllowUnsetRequest()
    {
        $allowed_unset = $this->basic_field;
        $allowed_unset['options']['allow_unset'] = true;

        return array(
            array (
                $this->basic_field,
                false
            ),
            array (
                $allowed_unset,
                true
            )
        );
    }

    public function testGetUnsetRequestData()
    {
        $this->assertEquals(null, $this->default_type->getUnsetRequestData($this->basic_field));
    }

    public function testIsValidRequestData()
    {
        $this->assertFalse($this->default_type->isValidRequestData($this->basic_field, null));
        $this->assertTrue($this->default_type->isValidRequestData($this->basic_field, 'example'));
        $this->assertTrue($this->default_type->isValidRequestData($this->basic_field, ''));
    }

    public function testProcessRequestData()
    {
        $data = '123';

        $processed = $this->default_type->processRequestData($this->basic_field, $data);

        $this->assertEquals($data, $processed);
    }

    public function testMakeView()
    {
        $form_handler = $this->getMockBuilder('\AVCMS\Core\Form\FormHandler')
        ->disableOriginalConstructor()
        ->getMock();

        $view = $this->default_type->makeView($this->basic_field, array('basic_field' => 'test'), $form_handler);

        $this->assertEquals('test', $view['value']);
    }
}
 