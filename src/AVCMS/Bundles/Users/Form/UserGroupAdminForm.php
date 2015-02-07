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
        
        $this->add('perm_default', 'radio', array(
            'label' => 'Default General Permission',
            'help' => 'Safe permissions like commenting or sending reports',
            'choices' => [
                'deny' => 'Deny',
                'allow' => 'Allow'
            ],
            'default' => 'allow'
        ));

        $this->add('elevated_default', 'radio', array(
            'label' => 'Default Elevated Permission',
            'help' => 'Special permissions like not having to see adverts',
            'choices' => [
                'deny' => 'Deny',
                'allow' => 'Allow'
            ],
            'default' => 'deny'
        ));

        $this->add('moderator_default', 'radio', array(
            'label' => 'Default Moderator Permission',
            'help' => 'Moderator-level permissions like deleting comments',
            'choices' => [
                'deny' => 'Deny',
                'allow' => 'Allow'
            ],
            'default' => 'deny'
        ));

        $this->add('admin_default', 'radio', array(
            'label' => 'Default Admin Permission',
            'help' => 'Admin permissions. Requires "Admin Panel Access" to be checked.',
            'choices' => [
                'deny' => 'Deny',
                'allow' => 'Allow'
            ],
            'default' => 'deny'
        ));

        $this->add('admin_panel_access', 'checkbox', [
            'label' => 'Admin Panel Access'
        ]);
    }
}
