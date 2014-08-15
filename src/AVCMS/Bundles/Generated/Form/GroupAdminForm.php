<?php

namespace AVCMS\Bundles\Generated\Form;

use AVCMS\Core\Form\FormBlueprint;

class GroupAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
    }
}