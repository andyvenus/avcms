<?php
/**
 * User: Andy
 * Date: 03/07/2014
 * Time: 12:25
 */

namespace AVCMS\Fields\Slug;

use AVCMS\Core\Validation\Rules\MustNotExist;
use AVCMS\Core\Validation\Validator;

class SlugForm
{
    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getFormField($form_blueprint)
    {
        $form_blueprint->add($this->config->getName(), 'text', array(
            'label' => $this->config->getLabel(),
            'template' => 'slug_text',
            'required' => true
        ));
    }

    public function getFormValidation(Validator $validator)
    {
        $validator->addRule(
            'slug',
            new MustNotExist($this->config->content->getModel(), 'slug', $this->config->content->getEntity()->getId()),
            'The URL Slug must be unique, slug already in use'
        );
    }
} 