<?php
/**
 * User: Andy
 * Date: 10/09/2014
 * Time: 13:59
 */

namespace AVCMS\Core\Form\Type;

class SelectType extends DefaultType
{
    public function getDefaultOptions($field)
    {
        if (isset($field['options']['choices_provider'])) {
            if (class_exists($field['options']['choices_provider']['class'])) {
                $choices_provider = new $field['options']['choices_provider']['class']();
                $field['options']['choices'] = call_user_func(array($choices_provider, 'getChoices'));
            }
        }

        return $field;
    }
}