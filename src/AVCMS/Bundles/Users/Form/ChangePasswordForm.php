<?php
/**
 * User: Andy
 * Date: 11/10/2014
 * Time: 21:55
 */

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;

class ChangePasswordForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('password1', 'password', [
            'label' => 'New Password'
        ]);

        $this->add('password2', 'password', [
            'label' => 'Re-enter password'
        ]);

        $this->setSuccessMessage('Password Updated');
    }
} 