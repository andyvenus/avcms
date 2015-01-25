<?php

namespace AVCMS\Bundles\Users\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class UsersAdminFiltersForm extends AdminFiltersForm
{
    public function __construct()
    {
        parent::__construct();

        $this->addAfter('order', 'group', 'select', [
            'label' => 'Group',
            'choices' => ['0' => 'All'],
            'choices_provider_service' => 'users.groups_choices_provider'
        ]);
    }
}
