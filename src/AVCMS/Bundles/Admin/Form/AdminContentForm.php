<?php
/**
 * User: Andy
 * Date: 12/06/2014
 * Time: 18:38
 */

namespace AVCMS\Bundles\Admin\Form;

use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Validation\Rules\MustNotExist;
use AVCMS\Core\Validation\Validatable;
use AVCMS\Core\Validation\Validator;

abstract class AdminContentForm extends FormBlueprint
{
    protected $item_id;

    public function __construct($item_id)
    {
        $this->item_id = $item_id;

        $this->add('published', 'radio', array(
            'label' => 'Publish',
            'default' => 1,
            'choices' => array(
                '1' => 'Publish / Scheduled Publish',
                '0' => 'Hidden'
            )
        ));

        $date = new \DateTime();

        $this->add('publish_date', 'text', array(
            'label' => 'Publish Time',
            'transform' => 'unixtimestamp',
            'default' => $date->format('Y-m-d H:i')
        ));

        $this->add('slug', 'text', array(
            'label' => 'URL Slug',
            'required' => true,
            'field_template' => '@admin/form_fields/slug_field.twig'
        ));
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('slug', new MustNotExist('AVCMS\Bundles\Blog\Model\Posts', 'slug', $this->item_id), 'The URL Slug must be unique, slug already in use');
    }
} 