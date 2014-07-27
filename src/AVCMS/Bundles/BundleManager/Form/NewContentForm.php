<?php
/**
 * User: Andy
 * Date: 27/07/2014
 * Time: 15:52
 */

namespace AVCMS\Bundles\BundleManager\Form;

use AVCMS\Core\Form\FormBlueprint;

class NewContentForm extends FormBlueprint
{
    public function __construct($database_table)
    {
        $this->add('database_table', 'hidden', array('default' => $database_table));

        $this->add('singular', 'text', array(
            'label' => 'Content Singular Name (like "post", "comment", "forum_post")',
            'required' => true
        ));

        $this->add('plural', 'text', array(
            'label' => 'Content Singular Name (like "posts", "comments", "forum_posts")',
            'required' => true
        ));
    }

    public function addColumnsFields($columns)
    {
        foreach ($columns as $column_name => $column) {
            $this->add('columns['.$column_name.'][entity]', 'checkbox', array(
                'label' => 'Add to entity',
                'default' => 1
            ));

            $this->add('columns['.$column_name.'][form_field]', 'select', array(
                'label' => 'Form Field',
                'choices' => array(
                    'none' => 'none',
                    'text' => 'text',
                    'textarea' => 'textarea',
                    'select' => 'select',
                    'checkbox' => 'checkbox',
                    'radio' => 'radio',
                )
            ));

            $this->add('columns['.$column_name.'][form_label]', 'text', array(
                'label' => 'Form Label',
                'default' => ucwords(str_replace('_', ' ', $column_name))
            ));
        }
    }
} 