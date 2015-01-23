<?php

namespace AVCMS\Bundles\CmsFoundation\Form;

use AV\Form\FormBlueprint;
use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;

class MenuAdminForm extends FormBlueprint
{
    protected $itemId;

    public function __construct($id = 0)
    {
        $this->itemId = $id;

        if ($id === 0) {
            $this->add('id', 'text', array(
                'label' => 'Identifier',
                'required' => true,
            ));
        }

        $this->add('label', 'text', array(
            'label' => 'Menu Name',
            'required' => true,
        ));
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('id', new MustNotExist('AVCMS\Bundles\CmsFoundation\Model\Menus', 'id', $this->itemId), 'That menu identifier is already in use');
    }
}
