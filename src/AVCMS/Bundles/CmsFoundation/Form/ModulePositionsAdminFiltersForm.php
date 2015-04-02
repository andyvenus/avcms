<?php

namespace AVCMS\Bundles\CmsFoundation\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class ModulePositionsAdminFiltersForm extends AdminFiltersForm
{
    public function __construct()
    {
        parent::__construct();

        $this->replace('order', 'select', array(
            'choices' => array(
                'a-z' => 'A-Z',
                'z-a' => 'Z-A',
            )
        ));
    }
}
