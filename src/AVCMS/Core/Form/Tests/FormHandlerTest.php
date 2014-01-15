<?php
/**
 * User: Andy
 * Date: 11/01/2014
 * Time: 11:12
 */

namespace AVCMS\Core\Form\Tests;

use AVCMS\Core\Form\FormHandler;
use AVCMS\Core\Form\Tests\Fixtures\BasicForm;
use AVCMS\Core\Form\Tests\Fixtures\StandardFormEntity;
use AVCMS\Core\Form\Tests\Fixtures\StandardForm;
use Symfony\Component\HttpFoundation\Request;

class FormHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $basic_form;

    protected $standard_form;

    protected $standard_form_request;

    public function setUp()
    {
        $this->basic_form = new BasicForm();
        $this->standard_form = new StandardForm();

        $this->standard_form_request = array(
            'name' => 'Example Name',
            'description' => 'Example Description',
            'category' => '3',
            'password' => '',
            'published' => 1
        );
    }

    public function testHandleSymfonyRequest()
    {
        if (!class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped("Symfony Request component not installed");

            return null;
        }

        $request = new Request(array(), array(
            'name' => 'Example Name'
        ));

        $form_handler = new FormHandler($this->basic_form);

        $form_handler->handleRequest($request, 'symfony');

        $this->assertEquals('Example Name', $form_handler->getData('name'));
        $this->assertTrue($form_handler->isSubmitted());
    }

    public function testHandleStandardRequest()
    {
        $request = $this->standard_form_request;

        $form_handler = new FormHandler($this->standard_form);

        $form_handler->handleRequest($request, 'standard');

        $this->assertEquals('Example Name', $form_handler->getData('name'));
        $this->assertTrue($form_handler->isSubmitted());
    }

    public function testIncompleteStandardRequest()
    {
        $request = $this->standard_form_request;

        unset($request['password']);

        $form_handler = new FormHandler($this->standard_form);

        $form_handler->handleRequest($request, 'standard');

        $this->assertEquals(null, $form_handler->getData('password'));
        $this->assertFalse($form_handler->isSubmitted());
    }

    /**
     * @dataProvider providerDefaultValues
     */
    public function testDefaultValues($default_values)
    {
        $form_handler = new FormHandler($this->standard_form);
        $form_handler->setDefaultValues($default_values);

        foreach ($default_values as $name => $value) {
            $this->assertEquals($default_values[$name], $form_handler->getData($name));
        }
    }

    /**
     * @dataProvider providerDefaultValues
     *
     * Test that these values take precedence over each other
     * Request > Entity Values > Default Values > Internal Form Default Values
     */
    public function testValuePriority($default_values)
    {
        $form_handler = new FormHandler($this->standard_form);

        $this->assertEquals('Default description', $form_handler->getData('description'));

        $form_handler->setDefaultValues($default_values);

        $this->assertEquals($default_values['description'], $form_handler->getData('description'));

        $form_handler->addEntity(new StandardFormEntity());

        $this->assertEquals("Entity Description", $form_handler->getData('description'));

        $form_handler->handleRequest($this->standard_form_request, 'standard');

        $this->assertEquals("Example Description", $form_handler->getData('description'));
    }

    public function testGetField()
    {
        $request = $this->standard_form_request;

        $form_handler = new FormHandler($this->standard_form);

        $form_handler->handleRequest($request, 'standard');

        $expected = array (
            'name' => 'name',
            'type' => 'text',
            'options' => array (
                'label' => 'Name'
            ),
            'value' => 'Example Name'
        );

        $this->assertEquals($expected, $form_handler->getField('name'));
    }

    public function providerDefaultValues()
    {
        return array(
            array(
                array(
                    'name' => 'Example name',
                    'description' => 'Example description',
                    'published' => 2
                )
            )
        );
    }
}
 