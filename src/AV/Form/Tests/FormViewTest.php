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
    private $formView;

    private $nameField;

    private $categoryField;

    public function setUp()
    {
        $this->formView = new FormView();

        $this->nameField = array (
            'name' => 'name',
            'type' => 'text',
            'options' => array (
                'label' => 'Name'
            ),
            'value' => 'Example Name'
        );

        $this->categoryField = array (
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
            'name' => $this->nameField,
            'category' => $this->categoryField
        );

        $this->formView->setFields($fields);

        $this->assertTrue(isset($this->formView->name));
        $this->assertTrue(isset($this->formView->category));
        $this->assertFalse(isset($this->formView->non_existant));
        $this->assertEquals($this->nameField, $this->formView->name);
        $this->assertEquals($this->categoryField, $this->formView->category);
    }

    public function testGetNullField()
    {
        $this->assertNull($this->formView->non_existant_var);
    }

    /**
     * @dataProvider providerInvalidFieldnames
     */
    public function testInvalidFieldnameException($field_name)
    {
        $this->setExpectedException('\Exception');

        $this->formView->setFields(array(
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

        $this->formView->setAction($url);

        $this->assertEquals($url, $this->formView->getAction());
    }

    public function testGetSetMethod()
    {
        $this->formView->setMethod('GET');

        $this->assertEquals('GET', $this->formView->getMethod());
    }

    public function testGetSetName()
    {
        $name = 'form_name_test';

        $this->formView->setName($name);

        $this->assertEquals($name, $this->formView->getName());
    }

    public function testValid()
    {
        $this->formView->setValid(true);
        $this->assertTrue($this->formView->isValid());
    }

    public function testEncoding()
    {
        $set = 'multipart/form-data';

        $this->formView->setEncoding($set);
        $this->assertEquals($set, $this->formView->getEncoding());
    }

    public function testHasErrors()
    {
        $this->assertNull($this->formView->getErrors());

        $this->formView->setErrors(array(new FormError('name', 'test')));
        $this->assertTrue($this->formView->hasErrors());
    }

    public function testJsonResponse()
    {
        $this->formView->setErrors(array(new FormError('name', 'test')));

        $jsonData = $this->formView->getJsonResponseData();

        $this->assertTrue($jsonData['has_errors']);
        $this->assertCount(1, $jsonData['errors']);
    }

    public function testTranslation()
    {
        $mockTranslator = $this->getMockBuilder('\AVCMS\Core\Translation\Translator')
            ->disableOriginalConstructor()
            ->getMock();

        $expectedTranslation = array(
            'Name Translated',
            'Category Translated',
            'One Translated',
            'Submit Translated',
            'Error Translated'
        );

        $map = array(
            array('Name', array(), null, null, $expectedTranslation[0]),
            array('Category', array(), null, null, $expectedTranslation[1]),
            array("One", array(), null, null, $expectedTranslation[2]),
            array("Submit Original", array(), null, null, $expectedTranslation[3]),
            array("Example Error", array(), null, null, $expectedTranslation[4])
        );

        $mockTranslator->expects($this->any())
            ->method('trans')
            ->will($this->returnValueMap($map));

        $this->formView->setTranslator($mockTranslator);

        $fields = array(
            'name' => $this->nameField,
            'category' => $this->categoryField
        );

        $this->formView->setFields($fields);

        $this->formView->setSubmitButtonLabel('Submit Original');

        $fields = $this->formView->getFields();

        $this->assertEquals($expectedTranslation[0], $fields['name']['options']['label']);
        $this->assertEquals($expectedTranslation[1], $fields['category']['options']['label']);
        $this->assertEquals($expectedTranslation[2], $fields['category']['options']['choices'][1]);
        $this->assertEquals($expectedTranslation[3], $this->formView->getSubmitButtonLabel());

        $formErrors = array(
            new FormError('name', 'Example Error', true)
        );

        $this->formView->setErrors($formErrors);

        $translatedErrors = $this->formView->getErrors();

        $this->assertEquals($expectedTranslation[4], $translatedErrors[0]->getMessage());
    }

    public function testTranslationNoTranslator()
    {
        $error = new FormError('name', 'A {param_name} error', true, ['param_name' => 'name']);

        $this->formView->setErrors([$error]);

        $finalErrors = $this->formView->getErrors();

        $this->assertEquals('A name error', $finalErrors[0]->getMessage());
    }

    public function testSections()
    {
        $sections = array('my_section' => array('label' => 'My Section'));
        $fields = array('example_field' => array('name' => 'example_field', 'options' => array('section' => 'my_section')));

        $this->formView->setSections($sections);

        $this->formView->setFields($fields);

        $this->assertEquals($sections, $this->formView->getSections());

        $resultingFields = $this->formView->getSectionFields('my_section');

        $this->assertArrayHasKey('example_field', $resultingFields);
    }

    public function testSuccessMessage()
    {
        $formBlueprint = new FormBlueprint();
        $formBlueprint->setSuccessMessage('Form Submitted Message');

        $this->formView->setFormBlueprint($formBlueprint);
        $this->formView->setSubmitted(true);

        $this->assertEquals('Form Submitted Message', $this->formView->getSuccessMessage('Form Submitted Message'));
    }

    public function testSubmitted()
    {
        $this->formView->setSubmitted(true);

        $this->assertTrue($this->formView->isSubmitted());
    }
}
 