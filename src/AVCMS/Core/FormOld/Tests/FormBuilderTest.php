<?php
/**
 * User: Andy
 * Date: 06/01/2014
 * Time: 16:21
 */

namespace AVCMS\Core\Form\Tests;


use AVCMS\Core\Form\FormBuilder;

class FormBuilderTest extends \PHPUnit_Framework_TestCase {
    public function addTest()
    {
        $form_builder = new FormBuilder();

        $form_builder->add('name', 'text', array(
            'label' => 'Name'
        ));

        $form_builder->add('description', 'textarea', array(
            'label' => 'Description'
        ));

        $form_builder->add('category', 'select', array(
            'label' => 'Category',
            'options' => array(
                'action' => 'Action',
                'arcade' => 'Arcade'
            )
        ));
    }
}
 