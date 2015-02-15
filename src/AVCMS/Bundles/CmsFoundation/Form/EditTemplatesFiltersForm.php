<?php
/**
 * User: Andy
 * Date: 15/02/15
 * Time: 13:33
 */

namespace AVCMS\Bundles\CmsFoundation\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class EditTemplatesFiltersForm extends AdminFiltersForm
{
    public function __construct($bundleChoices)
    {
        $this->add('bundle', 'select', [
            'choices' => $bundleChoices
        ]);

        parent::__construct();

        $this->add('admin_templates', 'checkbox', [
            'label' => 'Admin Templates'
        ]);

        $this->remove('order');
    }
}
