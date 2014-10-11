<?php

namespace AVCMS\Bundles\Users\Form;

use AVCMS\Core\Form\FormBlueprint;

class UserAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('username', 'text', array(
            'label' => 'Username',
            'required' => true,
            'attr' => array(
                'data-slug-target' => 'slug'
            ),
        ));

        $this->add('email', 'text', array(
            'label' => 'Email',
        ));
        
        $this->add('email_validated', 'checkbox', array(
            'label' => 'Email Validated',
        ));
        
        $this->add('about', 'textarea', array(
            'label' => 'About',
        ));

        $this->add('interests', 'textarea', array(
            'label' => 'Interests',
        ));
        
        $this->add('role_list', 'select', array(
            'label' => 'Group',
            'choices' => array(
                'ROLE_USER' => 'User',
                'ROLE_NOT_VALIDATED' => 'Not Validated',
                'ROLE_BANNED' => 'Banned',
                'ROLE_ADMIN' => 'Admin',
                'ROLE_SUPER_ADMIN' => 'Super Admin'
            )
        ));
        
        $this->add('location', 'text', array(
            'label' => 'Location',
        ));
        
        $this->add('website', 'text', array(
            'label' => 'Website',
        ));
        
        $this->add('joined', 'text', array(
            'label' => 'Joined',
            'transform' => 'unixtimestamp',
            'attr' => array(
                'data-date-selector' => '1'
            )
        ));
        
        $this->add('avatar', 'text', array(
            'label' => 'Avatar',
        ));

        $this->add('cover_image', 'text', array(
            'label' => 'Cover Image',
        ));
        
        $this->add('slug', 'text', array(
            'label' => 'Slug',
            'field_template' => '@admin/form_fields/slug_field.twig',
            'required' => true
        ));
    }
}