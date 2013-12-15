<?php

namespace Games\Form;

use AVCMS\Form\FormBuilder;
use AVCMS\Validation\Rules;
use AVCMS\Validation\Validator;

class GameForm extends FormBuilder {
    public function __construct() {
        $this->addTextInput('Name', 'name');

        //$this->addTextarea('Description', 'description');

        $this->addSelect("Category", 'category_id', array('1'=>'Action', '2'=>'SomethingElse'));

        $this->addButton("Submit", 'submit', 'submit');
    }

    public function validationRules(Validator $validator)
    {

    }
} 