<?php
/**
 * User: Andy
 * Date: 07/12/14
 * Time: 10:45
 */

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;

class ForgotPasswordForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('email', 'text', [
            'label' => 'Email address',
            'help' => 'The email address you used to register',
            'validation' => [
                ['rule' => 'EmailAddress'],
                ['rule' => 'MustExist', 'arguments' => ['AVCMS\Bundles\Users\Model\Users', 'email']]
            ]
        ]);

        $this->setSuccessMessage('Email sent, please check your inbox');
    }
} 