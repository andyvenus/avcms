<?php

namespace AVCMS\Bundles\Games\Form;

use AVCMS\Bundles\Admin\Form\AdminFiltersForm;

class FeedGamesAdminFiltersForm extends AdminFiltersForm
{
    public function __construct($feedChoices)
    {
        $this->setName('filter_form');

        $this->add('feed', 'select', [
            'choices' => $feedChoices,
            'translate_choices' => false,
        ]);

        $this->add('filetype', 'select', [
            'choices' => [
                'All' => 'All File Types',
                'Flash' => 'Flash',
                'HTML5' => 'HTML5',
                'Unity' => 'Unity',
                'Shockwave' => 'Shockwave'
            ]
        ]);

        $this->add('search', 'text', [
            'field_template' => '@admin/form_fields/search_field.twig'
        ]);

        $this->add('category', 'text', [
            'field_template' => '@admin/form_fields/search_field.twig',
            'placeholder' => 'Category'
        ]);
    }
}
