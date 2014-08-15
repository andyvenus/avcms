<?php
/**
 * User: Andy
 * Date: 13/08/2014
 * Time: 11:43
 */

namespace AVCMS\Bundles\CmsFoundation\Form;

use AVCMS\Core\Form\FormBlueprint;

class SettingsForm extends FormBlueprint
{
    public function __construct()
    {
        $this->addSection('main', 'Main');
        $this->addSection('boss', 'Secondary');

        $this->add('site_name', 'text', array('label' => 'Site Name', 'section' => 'main'));
        $this->add('template', 'select', array(
            'label' => 'Template',
            'section' => 'main',
            'choices' => array(
                'indigo' => 'Indigo',
                'something' => 'Something'
            )
        ));

        $this->setSuccessMessage("Settings Saved");
    }

    public function createSettingsFieldsFromArray(array $fields)
    {
        foreach ($fields as $field_name => $field) {
            if (isset($field['label'])) {
                $field['options']['label'] = $field['label'];
            }
            if (isset($field['help'])) {
                $field['options']['help'] = $field['help'];
            }
            if (isset($field['section'])) {
                $field['options']['section'] = $field['section'];
            }
            else {
                $field['options']['section'] = 'main';
            }

            $this->add($field_name, $field['type'], $field['options']);
        }
    }
} 