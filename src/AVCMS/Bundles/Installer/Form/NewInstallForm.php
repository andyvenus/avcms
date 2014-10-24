<?php
/**
 * User: Andy
 * Date: 24/10/14
 * Time: 13:44
 */

namespace AVCMS\Bundles\Installer\Form;

use AV\Form\FormBlueprint;

class NewInstallForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('host', 'text', [
            'label' => 'Database Host',
            'default' => 'localhost'
        ]);

        $this->add('username', 'text', [
            'label' => 'Database Username'
        ]);

        $this->add('password', 'password', [
            'label' => 'Database Password',
        ]);

        $this->add('database', 'text', [
            'label' => 'Database Name',
            'required' => true
        ]);

        $this->add('prefix', 'text', [
            'label' => 'Database Prefix',
            'default' => 'avcms_',
        ]);
    }
} 