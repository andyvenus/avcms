<?php

namespace AVCMS\Bundles\CmsFoundation\Form;

use AV\Form\FormBlueprint;

class MenuAdminForm extends FormBlueprint
{
    public function __construct($id = 0)
    {
        if ($id === 0) {
            $this->add('id', 'text', array(
                'label' => 'Identifier',
            ));

            $this->add('custom', 'hidden', array(
                'default' => '1'
            ));
        }

        $this->add('label', 'text', array(
            'label' => 'Label',
        ));
    }
}