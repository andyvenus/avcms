<?php

namespace AVCMS\Bundles\Pages\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class PagesAdminFiltersForm extends AdminFiltersForm
{
    public function __construct()
    {
        parent::__construct();

        $this->replace('order', 'select', array(
            'choices' => array(
                'newest' => 'Newest (by ID)',
                'oldest' => 'Oldest (by ID)',
                'publish-date-newest' => 'Newest (by publish date)',
                'publish-date-oldest' => 'Oldest (by publish date)',
                'a-z' => 'A-Z',
                'z-a' => 'Z-A',
                'top-hits' => 'Top Hits',
                'low-hits' => 'Least Hits',
            )
        ));
    }
}
