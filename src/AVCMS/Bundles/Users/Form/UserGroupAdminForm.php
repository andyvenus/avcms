<?php

namespace AVCMS\Bundles\Users\Form;

use AVCMS\Core\Form\FormBlueprint;

class UserGroupAdminForm extends FormBlueprint
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
            'choices' => [
                'deny' => 'Deny',
                'allow' => 'Allow'
            ],
            'default' => '0'
        ));
        
        $this->add('perm_default', 'radio', array(
            'label' => 'Default General Permission',
            'choices' => [
                'deny' => 'Deny',
                'allow' => 'Allow'
            ],
            'default' => '1'
        ));
    }
}