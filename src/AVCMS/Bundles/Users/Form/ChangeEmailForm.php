<?php
/**
 * User: Andy
 * Date: 13/10/2014
 * Time: 14:40
 */

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;

class ChangeEmailForm extends FormBlueprint
{
    public function __construct($currentEmail)
    {
        $this->add('email', 'text', [
            'label' => 'Email Address',
            'required' => true,
            'validation' => [
                ['rule' => 'EmailAddress']
            ],
            'default' => $currentEmail
        ]);

        $this->setSuccessMessage('Email Updated');
    }
} 
