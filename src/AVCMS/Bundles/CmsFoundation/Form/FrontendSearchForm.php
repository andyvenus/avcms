<?php
/**
 * User: Andy
 * Date: 15/11/14
 * Time: 13:05
 */

namespace AVCMS\Bundles\CmsFoundation\Form;

use AV\Form\FormBlueprint;

class FrontendSearchForm extends FormBlueprint
{
    public function __construct($searchContentTypes, $selectedContent = null)
    {
        $this->add('search', 'text', [
            'label' => 'Search',
            'attr' => ['placeholder' => 'Search']
        ]);

        $this->add('content_type', 'select', [
            'choices' => $searchContentTypes,
            'default' => $selectedContent
        ]);
    }
} 