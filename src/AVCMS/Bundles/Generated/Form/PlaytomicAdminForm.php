<?php

namespace AVCMS\Bundles\Generated\Form;

use AVCMS\Core\Form\FormBlueprint;

class PlaytomicAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
        
        $this->add('description', 'textarea', array(
            'label' => 'Description',
        ));
        
        $this->add('thumb_url', 'text', array(
            'label' => 'Thumb Url',
        ));
        
        $this->add('file_url', 'text', array(
            'label' => 'File Url',
        ));

        $this->add('width', 'text', array(
            'label' => 'Width'
        ));
        
        $this->add('height', 'text', array(
            'label' => 'Height'
        ));
    }
}