<?php
/**
 * User: Andy
 * Date: 28/03/2014
 * Time: 16:19
 */

namespace AV\Form\Tests\Type;

use AV\Form\Type\CollectionType;
use AV\Form\Type\TypeHandler;

class CollectionTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CollectionType
     */
    private $default_type;

    /**
     * @var array
     */
    private $collection_field;

    public function setUp()
    {
        $this->default_type = new CollectionType(new TypeHandler());

        $named_field1 = array(
            'name' => 'named_field1',
            'original_name' => 'collection_field[named_field1]',
            'type' => 'text',
            'options' => array(
                'label' => 'A Named Field',
                'allow_unset' => false
            )
        );

        $named_field2 = $named_field1;
        $named_field2['name'] = 'named_field2';
        $named_field2['original_name'] = 'collection_field[named_field2]';

        $this->collection_field = array(
            'name' => 'collection_field',
            'type' => 'collection',
            'fields' => array(
                'named_field1' => $named_field1,
                'named_field2' => $named_field2,
            ),
            'options' => array(
                'label' => 'Collection Field',
                'allow_unset' => false
            )
        );
    }

    public function testGetDefaultOptions()
    {
        $field = $this->default_type->getDefaultOptions($this->collection_field);

        $this->assertEquals($this->collection_field, $field);
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
        $allowed_unset = $this->collection_field;
        $allowed_unset['options']['allow_unset'] = true;

        return array(
            array (
                $this->collection_field,
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
        $this->assertEquals(null, $this->default_type->getUnsetRequestData($this->collection_field));
    }

    public function testIsValidRequestData()
    {
        $this->assertFalse($this->default_type->isValidRequestData($this->collection_field, null));
        $this->assertTrue($this->default_type->isValidRequestData($this->collection_field, array('named_field1' => 1)));
        $this->assertFalse($this->default_type->isValidRequestData($this->collection_field, ''));
    }

    public function testProcessRequestData()
    {
        $data = '123';

        $processed = $this->default_type->processRequestData($this->collection_field, $data);

        $this->assertEquals($data, $processed);
    }

    public function testMakeView()
    {
        $form_handler = $this->getMockBuilder('\AV\Form\FormHandler')
        ->disableOriginalConstructor()
        ->getMock();

        $form_handler->expects($this->any())
            ->method('fieldHasError')
            ->will($this->returnValue(true));

        $data = array(
            'collection_field' => array(
                'named_field1' => '123',
                'named_field2' => '456'
            )
        );

        $view = $this->default_type->makeView($this->collection_field, $data, $form_handler);

        $this->assertEquals('123', $view['fields']['named_field1']['value']);
        $this->assertEquals('456', $view['fields']['named_field2']['value']);
    }
}