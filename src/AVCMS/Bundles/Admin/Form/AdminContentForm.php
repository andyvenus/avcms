<?php
/**
 * User: Andy
 * Date: 12/06/2014
 * Time: 18:38
 */

namespace AVCMS\Bundles\Admin\Form;

use AV\Form\FormBlueprint;

abstract class AdminContentForm extends FormBlueprint
{
    protected $itemId;

    public function __construct($itemId)
    {
        $this->itemId = $itemId;

        $this->add('slug', 'text', array(
            'label' => 'URL Slug',
            'required' => true,
            'field_template' => '@admin/form_fields/slug_field.twig'
        ));

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
            'default' => $date->format('Y-m-d H:i'),
            'required' => true
        ));
    }
} 
