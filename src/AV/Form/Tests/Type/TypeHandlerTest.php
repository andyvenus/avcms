<?php
/**
 * User: Andy
 * Date: 29/03/2014
 * Time: 13:08
 */

namespace AV\Form\Tests\Type;

use AV\Form\Type\TypeHandler;

class TypeHandlerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var TypeHandler
     */
    private $type_handler;

    /**
     * @var \AV\Form\Type\DefaultType (Mock)
     */
    private $mock_type;

    public function setUp()
    {
        $this->type_handler = new TypeHandler();

        $this->mock_type = $this->getMock('AV\Form\Type\TypeInterface');
    }

    public function testGetType()
    {
        $default_type = $this->type_handler->getType('non-existant');
        $this->assertInstanceOf('AV\Form\Type\DefaultType', $default_type);

        $checkbox_type = $this->type_handler->getType('checkbox');
        $this->assertInstanceOf('AV\Form\Type\CheckboxType', $checkbox_type);
    }

    public function testAddType()
    {
        $this->type_handler->addType('mock_type', $this->mock_type);

        $this->assertNotInstanceOf('AV\Form\Type\DefaultType', $this->type_handler->getType('mock_type'));
    }

    public function testGetDefaultOptions()
    {
        $this->type_handler->addType('mock_type', $this->createMockTypeWithMockMethod('getDefaultOptions', 'default_options'));

        $this->assertEquals('default_options', $this->type_handler->getDefaultOptions(array('type' => 'mock_type')));
    }

    public function testIsValidRequestData()
    {
        $this->type_handler->addType('mock_type', $this->createMockTypeWithMockMethod('isValidRequestData', 'is_valid'));

        $this->assertEquals('is_valid', $this->type_handler->isValidRequestData(array('type' => 'mock_type'), array()));
    }

    public function testAllowUnsetRequest()
    {
        $this->type_handler->addType('mock_type', $this->createMockTypeWithMockMethod('allowUnsetRequest', 'allow_unset'));

        $this->assertEquals('allow_unset', $this->type_handler->allowUnsetRequest(array('type' => 'mock_type')));
    }

    public function testProcessRequestData()
    {
        $this->type_handler->addType('mock_type', $this->createMockTypeWithMockMethod('processRequestData', 'process_request'));

        $this->assertEquals('process_request', $this->type_handler->processRequestData(array('type' => 'mock_type'), array()));
    }

    public function testGetUnsetRequestData()
    {
        $this->type_handler->addType('mock_type', $this->createMockTypeWithMockMethod('getUnsetRequestData', 'unset_data'));

        $this->assertEquals('unset_data', $this->type_handler->getUnsetRequestData(array('type' => 'mock_type'), array()));
    }

    public function testMakeView()
    {
        $this->type_handler->addType('mock_type', $this->createMockTypeWithMockMethod('makeView', 'make_view'));

        $mock_form_handler = $this->getMockBuilder('AV\Form\FormHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals('make_view', $this->type_handler->makeView(array('type' => 'mock_type'), array(), $mock_form_handler));
    }

    private function createMockTypeWithMockMethod($method, $response)
    {
        // Create a stub for the SomeClass class.
        $stub = $this->getMock('AV\Form\Type\TypeInterface');

        // Configure the stub.
        $stub->expects($this->any())
            ->method($method)
            ->will($this->returnValue($response));

        return $stub;
    }
}
 