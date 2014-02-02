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
}
 