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

            if (isset($field['choices_provider'])) {
                if (class_exists($field['choices_provider']['class'])) {
                    $choices_provider = new $field['choices_provider']['class']();
                    $field['options']['choices'] = call_user_func(array($choices_provider, 'getChoices'));
                }
            }

            $this->add($field_name, $field['type'], $field['options']);
        }
    }

    public function createSectionsFromArray(array $sections)
    {
        foreach ($sections as $id => $section) {
            $this->addSection($id, $section['label']);
        }
    }
} 