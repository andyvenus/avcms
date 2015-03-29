<?php
/**
 * User: Andy
 * Date: 05/11/14
 * Time: 11:53
 */

namespace AVCMS\Bundles\Reports\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class ReportsAdminFiltersForm extends AdminFiltersForm
{
    public function __construct($contentTypes)
    {
        parent::__construct();

        $this->addBefore('search', 'contentType', 'select', [
            'label' => 'Content Type',
            'choices' => $contentTypes
        ]);
    }
} 
