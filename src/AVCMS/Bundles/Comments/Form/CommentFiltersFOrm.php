<?php
/**
 * User: Andy
 * Date: 30/10/14
 * Time: 22:58
 */

namespace AVCMS\Bundles\Comments\Form;

use AV\Form\FormBlueprint;
use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class CommentFiltersForm extends AdminFiltersForm
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