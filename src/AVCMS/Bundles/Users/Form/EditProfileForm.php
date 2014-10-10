<?php
/**
 * User: Andy
 * Date: 08/10/2014
 * Time: 11:53
 */

namespace AVCMS\Bundles\Users\Form;

use AVCMS\Core\Form\FormBlueprint;

class EditProfileForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('location', 'text', [
            'label' => 'Location'
        ]);

        $this->add('interests', 'textarea', [
            'label' => 'Interests'
        ]);

        $this->add('about_me', 'textarea', [
            'label' => 'About Me'
        ]);

        $this->add('website', 'text', [
            'label' => 'Website'
        ]);

        $this->add('avatar_file', 'file', [
            'label' => 'Avatar',
            'validation' => [
                ['rule' => 'SymfonyFile', 'arguments' => [
                    '204800',
                    [
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'jpeg' => 'image/jpeg'
                    ],
                    'KB'
                ]],
                ['rule' => 'SymfonyImageFile']
            ]
        ]);

        $this->add('cover_image', 'file', [
            'label' => 'Cover Image',
            'validation' => [
                ['rule' => 'SymfonyFile', 'arguments' => [
                    '1024000',
                    [
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'jpeg' => 'image/jpeg'
                    ],
                    'MB'
                ]],
                ['rule' => 'SymfonyImageFile']
            ]
        ]);

        $this->setName('edit_profile');
        $this->setSuccessMessage('Profile Updated');
    }
} 