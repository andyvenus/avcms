<?php
/**
 * User: Andy
 * Date: 27/07/2014
 * Time: 15:52
 */

namespace AVCMS\BundlesDev\BundleManager\Form;

use AV\Form\FormBlueprint;

class NewContentForm extends FormBlueprint
{
    public function __construct($new_content, $database_table)
    {
        if ($new_content === true) {
            $this->add('database_table', 'hidden', array('default' => $database_table));

            $this->add('singular', 'text', array(
                'label' => 'Content Singular Name (like "post", "comment", "forum_post")',
                'required' => true
            ));

            $this->add('plural', 'text', array(
                'label' => 'Content Plural Name (like "posts", "comments", "forum_posts")',
                'required' => true
            ));

            $this->add('admin_sections', 'radio', array(
                'label' => 'Generate Admin Section (form, templates, controller)',
                'choices' => array(
                    '1' => 'Yes',
                    '0' => 'No'
                ),
                'default' => '1'
            ));
        }
    }

    public function addColumnsFields($columns)
    {
        foreach ($columns as $column_name => $column) {
            if (isset($column['entity']) && $column['entity'] === true) {
                $form_field_type = 'hidden';
            }
            else {
                $form_field_type = 'checkbox';
            }

            $this->add('columns['.$column_name.'][entity]', $form_field_type, array(
                'label' => 'Add to entity',
                'default' => 1
            ));

            if (isset($column['form']) && $column['form'] === true) {
                $form_field_type = 'hidden';
            }
            else {
                $form_field_type = 'select';
            }
            $this->add('columns['.$column_name.'][form_field]', $form_field_type, array(
                'label' => 'Form Field',
                'choices' => array(
                    'none' => 'none',
                    'text' => 'text',
                    'textarea' => 'textarea',
                    'select' => 'select',
                    'checkbox' => 'checkbox',
                    'radio' => 'radio',
                ),
                'default' => 'none'
            ));

            $this->add('columns['.$column_name.'][form_label]', 'text', array(
                'label' => 'Form Label',
                'default' => ucwords(str_replace('_', ' ', $column_name))
            ));
        }
    }
} 