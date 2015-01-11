<?php

namespace AVCMS\Bundles\Adverts\Form;

use AV\Form\FormBlueprint;

class AdvertAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
        
        $this->add('content', 'textarea', array(
            'label' => 'Content',
        ));
    }
}