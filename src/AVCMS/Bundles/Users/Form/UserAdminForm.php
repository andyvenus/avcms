<?php

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;
use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;

class UserAdminForm extends FormBlueprint
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;

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
            'choices_provider_service' => 'users.groups_choices_provider'
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

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('username', new MustNotExist('AVCMS\Bundles\Users\Model\Users', 'username', $this->userId), 'The entered username is already in use');
        $validator->addRule('email', new MustNotExist('AVCMS\Bundles\Users\Model\Users', 'email', $this->userId), 'The entered email is already in use');
        $validator->addRule('slug', new MustNotExist('AVCMS\Bundles\Users\Model\Users', 'slug', $this->userId), 'The URL Slug must be unique, slug already in use');
    }
}
