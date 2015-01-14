<?php

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;

class UserGroupAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name',
        ));
        
        $this->add('flood_control_time', 'text', array(
            'label' => 'Flood Control Time (seconds)',
            'help' => 'Length of time before users can post another comment and other similar activities'
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

        $this->add('admin_panel_access', 'checkbox', [
            'label' => 'Admin Panel Access'
        ]);
    }
}
