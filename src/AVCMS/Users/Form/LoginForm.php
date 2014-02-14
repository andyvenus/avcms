<?php
/**
 * User: Andy
 * Date: 10/02/2014
 * Time: 16:13
 */

namespace AVCMS\Users\Form;

use AVCMS\Core\Form\FormBlueprint;

class LoginForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('username', 'text', array(
            'label' => 'Username'
        ));

        $this->add('password', 'password', array(
            'label' => 'Password'
        ));
    }
} 