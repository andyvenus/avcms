<?php

namespace AVCMS\Bundles\TestBundle\Form;

use AVCMS\Core\Form\FormBlueprint;

class MexAdminForm extends FormBlueprint
{
    public function __construct()
    {

        $this->add('fog_id', 'text', array(
            'label' => 'Fog Id'
        ));

        $this->add('name', 'textarea', array(
            'label' => 'Name'
        ));

        $this->add('description', 'checkbox', array(
            'label' => 'Description'
        ));

        $this->add('file_url', 'select', array(
            'label' => 'File Url'
        ));
    }
}