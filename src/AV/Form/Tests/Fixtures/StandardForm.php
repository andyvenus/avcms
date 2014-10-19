<?php
/**
 * User: Andy
 * Date: 11/01/2014
 * Time: 18:41
 */

namespace AV\Form\Tests\Fixtures;


use AV\Form\FormBlueprint;

class StandardForm extends FormBlueprint {
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name'
        ));

        $this->add('description', 'textarea', array(
            'label' => 'Description',
            'default' => 'Default description'
        ));

        $this->add('category', 'select', array(
            'label' => 'Category',
            'choices' => array(
                '1' => 'Cat One',
                '2' => 'Cat Two',
                '3' => 'Cat Three',
            )
        ));

        $this->add('password', 'password', array(
            'label' => 'Password'
        ));

        $this->add('published', 'radio', array(
            'choices' => array(
                '1' => 'Published',
                '2' => 'Unpublished'
            ),
            'default' => '1',
            'label' => 'Published'
        ));
    }

    public function getName()
    {
        return 'standard_form';
    }
} 