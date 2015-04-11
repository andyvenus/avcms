<?php
/**
 * User: Andy
 * Date: 27/09/2014
 * Time: 13:06
 */

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;

class RegistrationForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('username', 'text', array(
            'label' => 'Username',
            'required' => true,
            'validation' => array(
                [
                'rule' => 'MustNotExist',
                'arguments' => array('AVCMS\Bundles\Users\Model\Users', 'username'),
                'error_message' => 'Sorry that username is already in use',
                ],
                [
                    'rule' => 'Length',
                    'arguments' => array('3', '24')
                ]
            )
        ));

        $this->add('email', 'text', array(
            'label' => 'Email Address',
            'required' => true,
            'validation' => array(
               [
                    'rule' => 'MustNotExist',
                    'arguments' => array('AVCMS\Bundles\Users\Model\Users', 'email'),
                    'error_message' => 'An account is already registered using that email address',
                ],
                [
                    'rule' => 'EmailAddress'
                ]
            )
        ));

        $this->add('password1', 'password', [
            'required' => true,
            'label' => 'Password',
            'validation' => [
                [
                    'rule' => 'Length',
                    'arguments' => array('4')
                ]
            ]
        ]);

        $this->add('password2', 'password', [
            'required' => true,
            'label' => 'Confirm Password'
        ]);

        $this->add('recaptcha', 'recaptcha');
    }
} 
