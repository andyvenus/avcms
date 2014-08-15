<?php

namespace AVCMS\Bundles\Generated\Form;

use AVCMS\Core\Form\FormBlueprint;

class AdvertAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('ad_name', 'text', array(
            'label' => 'Name',
        ));
        
        $this->add('ad_content', 'textarea', array(
            'label' => 'Advert HTML',
        ));
    }
}