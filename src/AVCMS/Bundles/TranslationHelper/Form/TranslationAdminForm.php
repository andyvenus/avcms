<?php

namespace AVCMS\Bundles\TranslationHelper\Form;

use AV\Form\FormBlueprint;

class TranslationAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('language', 'text', array(
            'label' => 'Language Code',
            'required' => true,
            'validation' => [
                ['rule' => 'Length', 'arguments' => [2, 2]]
            ]
        ));
        
        $this->add('country', 'text', array(
            'label' => 'Country',
            'validation' => [
                ['rule' => 'Length', 'arguments' => [null, 2]]
            ]
        ));

        $this->add('name', 'text', array(
            'label' => 'Name',
            'required' => true
        ));

        $this->add('public', 'checkbox', array(
            'label' => 'Public',
        ));
    }
}
