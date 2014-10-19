<?php
/**
 * User: Andy
 * Date: 16/01/2014
 * Time: 11:34
 */

namespace AV\Form\Tests;

use AV\Form\FormBlueprint;
use AV\Form\FormError;
use AV\Form\FormView;

class FormViewTest extends \PHPUnit_Framework_TestCase
{
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

    public function testEncoding()
    {
        $set = 'multipart/form-data';

        $this->form_view->setEncoding($set);
        $this->assertEquals($set, $this->form_view->getEncoding());
    }

    public function testHasErrors()
    {
        $this->assertNull($this->form_view->getErrors());

        $this->form_view->setErrors(array(new FormError('name', 'test')));
        $this->assertTrue($this->form_view->hasErrors());
    }

    public function testJsonResponse()
    {
        $this->form_view->setErrors(array(new FormError('name', 'test')));

        $json_data = $this->form_view->getJsonResponseData();

        $this->assertTrue($json_data['has_errors']);
        $this->assertCount(1, $json_data['errors']);
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
            'Submit Translated',
            'Error Translated'
        );

        $map = array(
            array('Name', array(), null, null, $expected_translation[0]),
            array('Category', array(), null, null, $expected_translation[1]),
            array("One", array(), null, null, $expected_translation[2]),
            array("Submit Original", array(), null, null, $expected_translation[3]),
            array("Example Error", array(), null, null, $expected_translation[4])
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

        $form_errors = array(
            new FormError('name', 'Example Error', true)
        );

        $this->form_view->setErrors($form_errors);

        $translated_errors = $this->form_view->getErrors();

        $this->assertEquals($expected_translation[4], $translated_errors[0]->getMessage());
    }

    public function testSections()
    {
        $sections = array('my_section' => array('label' => 'My Section'));
        $fields = array('example_field' => array('name' => 'example_field', 'options' => array('section' => 'my_section')));

        $this->form_view->setSections($sections);

        $this->form_view->setFields($fields);

        $this->assertEquals($sections, $this->form_view->getSections());

        $resulting_fields = $this->form_view->getSectionFields('my_section');

        $this->assertArrayHasKey('example_field', $resulting_fields);
    }

    public function testSuccessMessage()
    {
        $form_blueprint = new FormBlueprint();
        $form_blueprint->setSuccessMessage('Form Submitted Message');

        $this->form_view->setFormBlueprint($form_blueprint);
        $this->form_view->setSubmitted(true);

        $this->assertEquals('Form Submitted Message', $this->form_view->getSuccessMessage('Form Submitted Message'));
    }

    public function testSubmitted()
    {
        $this->form_view->setSubmitted(true);

        $this->assertTrue($this->form_view->isSubmitted());
    }
}
 