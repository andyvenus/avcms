<?php
/**
 * User: Andy
 * Date: 25/10/14
 * Time: 13:56
 */

namespace AVCMS\Bundles\Installer\Form;

use AV\Form\FormBlueprint;

class CreateAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('username', 'text', [
            'label' => 'Username',
            'required' => true
        ]);

        $this->add('password1', 'password', [
            'label' => 'Password',
            'required' => true
        ]);

        $this->add('password2', 'password', [
            'label' => 'Confirm Password',
            'required' => true
        ]);

        $this->add('email', 'text', [
            'label' => 'Email',
            'required' => true
        ]);
    }
} 
