<?php

namespace AVCMS\Bundles\CmsFoundation\Form;

use AV\Form\FormBlueprint;

class MenuItemAdminForm extends FormBlueprint
{
    public function __construct()
    {
        $this->setName('menu_item_form');

        $this->add('label', 'text', array(
            'label' => 'Label',
            'required' => true,
        ));

        $this->add('icon', 'text', array(
            'label' => 'Icon',
            'required' => true,
        ));
    }
}
