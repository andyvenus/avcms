<?php
/**
 * User: Andy
 * Date: 08/10/2014
 * Time: 11:53
 */

namespace AVCMS\Bundles\Users\Form;

use AV\Form\FormBlueprint;

class EditProfileForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('location', 'text', [
            'label' => 'Location',
            'validation' => [
                ['rule' => 'Length', 'arguments' => [null, 50]]
            ]
        ]);

        $this->add('interests', 'textarea', [
            'label' => 'Interests',
            'validation' => [
                ['rule' => 'Length', 'arguments' => [null, 300]]
            ]
        ]);

        $this->add('about', 'textarea', [
            'label' => 'About Me',
            'validation' => [
                ['rule' => 'Length', 'arguments' => [null, 300]]
            ]
        ]);

        $this->add('website', 'text', [
            'label' => 'Website',
            'validation' => [
                ['rule' => 'Length', 'arguments' => [null, 100]]
            ]
        ]);

        $this->add('receive_emails', 'checkbox', [
            'label' => 'Enable Emails',
            'help' => 'If off you will not receive any notification or newsletter emails'
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
