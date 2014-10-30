<?php
/**
 * User: Andy
 * Date: 30/10/14
 * Time: 22:58
 */

namespace AVCMS\Bundles\Comments\Form;

use AV\Form\FormBlueprint;

class CommentFiltersForm extends FormBlueprint
{
    public function __construct($contentTypes)
    {
        $this->setName('filter_form');

        $this->add('contentType', 'select', [
            'label' => 'Content Type',
            'choices' => $contentTypes
        ]);
    }
} 