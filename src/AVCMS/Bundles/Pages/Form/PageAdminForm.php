<?php

namespace AVCMS\Bundles\Pages\Form;

use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;
use AVCMS\Bundles\Admin\Form\AdminContentForm;

class PageAdminForm extends AdminContentForm
{
    public function __construct($pageId)
    {
        $this->add('title', 'text', array(
            'label' => 'Title',
            'attr' => array(
                'data-slug-target' => 'slug',
            ),
            'required' => true,
        ));
        
        $this->add('content', 'textarea', array(
            'label' => 'Content',
            'required' => true,
            'attr' => [
                'rows' => 10,
                'data-html-editor' => 1
            ]
        ));

        $this->add('show_title', 'checkbox', [
            'label' => 'Show Page Title',
            'default' => 1
        ]);

        parent::__construct($pageId);
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('slug', new MustNotExist('AVCMS\Bundles\Pages\Model\Pages', 'slug', $this->item_id), 'The URL Slug must be unique, slug already in use');
    }
}
