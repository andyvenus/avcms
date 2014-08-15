<?php

namespace AVCMS\Bundles\Generated\Form;

use AVCMS\Core\Form\FormBlueprint;

class GameAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
        
        $this->add('description', 'textarea', array(
            'label' => 'Description',
        ));
    }
}