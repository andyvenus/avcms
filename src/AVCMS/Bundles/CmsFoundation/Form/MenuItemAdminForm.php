<?php

namespace AVCMS\Bundles\CmsFoundation\Form;

use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Validation\Rules\MustNotExist;
use AVCMS\Core\Validation\Validator;

class MenuItemAdminForm extends FormBlueprint
{
    public function __construct($id)
    {
        $this->id = $id;

        $this->setName('menu_item_form');

        if ($id === 0) {
            $this->add('id', 'text', array(
                'label' => 'Identifier (permanent)'
            ));
        }

        $this->add('label', 'text', array(
            'label' => 'Label',
        ));

        $this->add('type', 'select', array(
            'label' => 'Target Type',
            'choices' => array(
                'url' => 'URL',
                'route' => 'Route Name',
                'category' => 'Category',
            )
        ));

        $this->add('target', 'text', array(
            'label' => 'Target'
        ));

        $this->add('icon', 'text', array(
            'label' => 'Icon'
        ));

        $this->add('enabled', 'checkbox', array(
            'label' => 'Enabled',
            'default' => 1
        ));
    }

    public function getValidationRules(Validator $validator)
    {
        if ($this->id === 0) {
            $validator->addRule('id', new MustNotExist('AVCMS\Bundles\CmsFoundation\Model\MenuItems', 'id', $this->id));
        }
    }
}