<?php

namespace AVCMS\Bundles\TestBundle\Form;

use AVCMS\Core\Form\FormBlueprint;

class LolAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
    }
}