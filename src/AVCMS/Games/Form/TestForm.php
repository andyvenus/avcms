<?php
/**
 * User: Andy
 * Date: 11/01/2014
 * Time: 18:41
 */

namespace AVCMS\Games\Form;

use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Validation\Rules\Length;
use AVCMS\Core\Validation\Rules\MinLength;
use AVCMS\Core\Validation\Validator;

class TestForm extends FormBlueprint {
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name'
        ));

        $this->add('description', 'textarea', array(
            'label' => 'Description',
            'default' => 'Default description'
        ));

        $this->add('category_id', 'select', array(
            'label' => 'Category',
            'choices' => array(
                '1' => 'Cat One',
                '2' => 'Cat Two',
                '3' => 'Cat Three',
            )
        ));

        $this->add('hidden_one', 'hidden', array(
            'label' => 'You should not see this',
            'default' => 'dino'
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

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('name', new Length(10), 'The name must be at least {min} fuckity characters');
    }

    public function getName()
    {
        return 'test_form';
    }
} 