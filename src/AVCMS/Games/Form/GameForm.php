<?php

namespace AVCMS\Games\Form;

use AVCMS\Core\Form\FormBuilder;
use AVCMS\Core\Validation\Rules;
use AVCMS\Core\Validation\Validator;

class GameForm extends FormBuilder {
    public function __construct()
    {
        $this->addTextInput('Name', 'name');

        //$this->addTextarea('Description', 'description');

        $this->addSelect("Category", 'category_id', array('1'=>'Action', '2'=>'SomethingElse'));

        $this->addButton("Submit", 'submit', 'submit');
    }

    public function validationRules(Validator $validator)
    {
        $validator->addRule('name', new Rules\Length(1, 5));
    }
} 