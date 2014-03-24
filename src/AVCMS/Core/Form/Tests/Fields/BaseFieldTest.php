<?php
/**
 * User: Andy
 * Date: 24/03/2014
 * Time: 18:58
 */

namespace AVCMS\Core\Form\Tests\Fields;


use AVCMS\Core\Form\Fields\BaseField;

class BaseFieldTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var BaseField
     */
    private $base_field;

    public function setUp()
    {
        $this->base_field = new BaseField('base_field', array(
            'label' => 'The Label',
            'value' => 'Default Value',
            'empty_value' => 'Empty Value',
            'allow_unset' => true
        ));
    }

    public function testHandleRequestData()
    {
        $this->base_field->handleRequestData('Request Value');

        $this->assertEquals('Request Value', $this->base_field->getValue());
    }

    public function testAllowUnset()
    {
        $base_field = new BaseField('base_field', array('allow_unset' => true));
        $this->assertTrue($base_field->allowUnset());

        $base_field = new BaseField('base_field', array());
        $this->assertFalse($base_field->allowUnset());
    }

    public function testIsRequired()
    {
        $base_field = new BaseField('base_field', array());
        $this->assertFalse($base_field->isRequired());

        $base_field = new BaseField('base_field', array('required' => false));
        $this->assertFalse($base_field->isRequired());

        $base_field = new BaseField('base_field', array('required' => true));
        $this->assertTrue($base_field->isRequired());
    }

    public function testGetName()
    {
        $this->assertEquals('base_field', $this->base_field->getName());
    }

    public function testGetOption()
    {
        $this->assertEquals('The Label', $this->base_field->getOption('label'));
    }

    public function testSetOption()
    {
        $this->base_field->setOption('example_option', 'Example Value');

        $this->assertEquals('Example Value', $this->base_field->getOption('example_option'));
    }

    public function testGetDefaultOptions()
    {
        $this->assertEquals(array(), $this->base_field->getDefaultOptions());
    }
}
 