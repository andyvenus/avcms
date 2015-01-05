<?php

namespace AVCMS\Bundles\CmsFoundation\Form;

use AV\Form\FormBlueprint;
use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;

class MenuItemAdminForm extends FormBlueprint
{
    public function __construct($id, $typeChoices)
    {
        $this->id = $id;

        $this->setName('menu_item_form');

        if ($id === 0) {
            $this->add('id', 'text', array(
                'label' => 'Identifier (permanent)',
                'required' => true,
            ));
        }

        $this->add('label', 'text', array(
            'label' => 'Label',
            'required' => true,
        ));

        $this->add('target', 'text', array(
            'label' => 'Target',
            'required' => true,
        ));

        $this->add('icon', 'text', array(
            'label' => 'Icon',
            'required' => true,
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
