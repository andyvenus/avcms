<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 11:34
 */

namespace AVCMS\Core\Form\Tests;


use AVCMS\Core\Form\FormView;

class FormViewTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var FormView
     */
    private $form_view;

    private $name_field;

    private $category_field;

    public function setUp()
    {
        $this->form_view = new FormView();

        $this->name_field = array (
            'name' => 'name',
            'type' => 'text',
            'options' => array (
                'label' => 'Name'
            ),
            'value' => 'Example Name'
        );

        $this->category_field = array (
            'name' => 'category',
            'type' => 'select',
            'options' => array (
                'label' => 'Category',
                'choices' => array(
                    '1' => 'One',
                    '2' => 'Two'
                )
            )
        );
    }

    public function testGetSetField()
    {
        $fields = array(
            'name' => $this->name_field,
            'category' => $this->category_field
        );

        $this->form_view->setFields($fields);

        $this->assertTrue(isset($this->form_view->name));
        $this->assertTrue(isset($this->form_view->category));
        $this->assertFalse(isset($this->form_view->non_existant));
        $this->assertEquals($this->name_field, $this->form_view->name);
        $this->assertEquals($this->category_field, $this->form_view->category);
    }

    public function testGetNullField()
    {
        $this->assertNull($this->form_view->non_existant_var);
    }

    /**
     * @dataProvider providerInvalidFieldnames
     */
    public function testInvalidFieldnameException($field_name)
    {
        $this->setExpectedException('\Exception');

        $this->form_view->setFields(array(
            $field_name => array('name' => $field_name)
        ));
    }

    public function providerInvalidFieldnames()
    {
        return array(
            array('params')
        );
    }

    public function testGetSetAction()
    {
        $url = 'http://www.example.com/form.php';

        $this->form_view->setAction($url);

        $params = $this->form_view->getParams();

        $this->assertEquals($url, $params['action']);
    }

    public function testGetSetMethod()
    {
        $this->form_view->setMethod('GET');

        $params = $this->form_view->getParams();

        $this->assertEquals('GET', $params['method']);
    }

    public function testTranslation()
    {
        $mock_translator = $this->getMockBuilder('\AVCMS\Core\Translation\Translator')
            ->disableOriginalConstructor()
            ->getMock();

        $expected_translation = array(
            'Name Translated',
            'Category Translated',
            'One Translated',
            'Submit Translated'
        );

        $map = array(
            array('Name', array(), null, null, $expected_translation[0]),
            array('Category', array(), null, null, $expected_translation[1]),
            array("One", array(), null, null, $expected_translation[2]),
            array("Submit Original", array(), null, null, $expected_translation[3])
        );

        $mock_translator->expects($this->any())
            ->method('trans')
            ->will($this->returnValueMap($map));

        $this->form_view->setTranslator($mock_translator);

        $fields = array(
            'name' => $this->name_field,
            'category' => $this->category_field
        );

        $this->form_view->setFields($fields);

        $this->form_view->setSubmitButtonLabel('Submit Original');

        $fields = $this->form_view->getFields();

        $this->assertEquals($expected_translation[0], $fields['name']['options']['label']);
        $this->assertEquals($expected_translation[1], $fields['category']['options']['label']);
        $this->assertEquals($expected_translation[2], $fields['category']['options']['choices'][1]);
        $this->assertEquals($expected_translation[3], $this->form_view->getSubmitButtonLabel());
    }
}
 