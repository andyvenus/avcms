<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 18:38
 */

namespace AVCMS\Bundles\FacebookConnect\Form;

use AV\Form\FormBlueprint;
use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;

class FacebookAccountForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('username', 'text', [
            'label' => 'Username'
        ]);
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('username', new MustNotExist('AVCMS\Bundles\Users\Model\Users', 'username'), 'Sorry that username is already in use');
    }
}
