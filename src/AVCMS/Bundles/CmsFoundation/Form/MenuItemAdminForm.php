<?php

namespace AVCMS\Bundles\CmsFoundation\Form;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\CmsFoundation\Form\ChoicesProvider\IconChoicesProvider;

class MenuItemAdminForm extends FormBlueprint
{
    public function __construct(IconChoicesProvider $iconChoices)
    {
        $this->setName('menu_item_form');

        $this->add('label', 'text', array(
            'label' => 'Label',
            'required' => true,
        ));

        $this->add('icon', 'select', array(
            'label' => 'Icon',
            'required' => true,
            'choices_provider' => $iconChoices,
            'attr' => [
                'class' => 'no_select2 icon-selector'
            ]
        ));
    }
}
