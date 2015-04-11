<?php
/**
 * User: Andy
 * Date: 12/01/15
 * Time: 18:38
 */

namespace AVCMS\Bundles\FacebookConnect\Form;

use AV\Form\FormBlueprint;

class FacebookAccountForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('username', 'text', [
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
        ]);
    }
}
