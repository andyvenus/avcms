<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 16:13
 */

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;

class LoginForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('username', 'text', array(
            'label' => 'Username',
            'required' => true
        ));

        $this->add('password', 'password', array(
            'label' => 'Password',
            'required' => true
        ));

        $this->add('remember', 'checkbox', array(
            'label' => 'Remember me',
            'checked' => true
        ));
    }
} 