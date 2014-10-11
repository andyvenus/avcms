<?php

namespace AVCMS\Bundles\TestBundle\Form;

use AVCMS\Core\Form\FormBlueprint;

class GroupAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
        
        $this->add('flood_control_time', 'text', array(
            'label' => 'Flood Control Time',
        ));
        
        $this->add('admin_default', 'radio', array(
            'label' => 'Default Admin Permission',
        ));
        
        $this->add('perm_default', 'radio', array(
            'label' => 'Default General Permission',
        ));
    }
}