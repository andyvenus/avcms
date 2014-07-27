<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 16:13
 */

namespace AVCMS\Bundles\Users\Form;

use AVCMS\Core\Form\FormBlueprint;

class LoginForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('identifier', 'text', array(
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