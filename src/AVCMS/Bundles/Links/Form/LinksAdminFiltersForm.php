<?php

namespace AVCMS\Bundles\Links\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class LinksAdminFiltersForm extends AdminFiltersForm
{
    public function __construct()
    {
        parent::__construct();

        $this->replace('order', 'select', array(
            'choices' => array(
                'newest' => 'Newest (by ID)',
                'oldest' => 'Oldest (by ID)',
                'a-z' => 'A-Z',
                'z-a' => 'Z-A',
            )
        ));
    }
}
