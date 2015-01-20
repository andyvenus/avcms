<?php

namespace AVCMS\Bundles\Referrals\Form;

use AV\Form\FormBlueprint;

class ReferralAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));

        $this->add('user_id', 'text', array(
            'label' => 'User',
            'attr' => array(
                'class' => 'user_selector no_select2'
            )
        ));
    }
}
