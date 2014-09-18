<?php
/**
 * User: Andy
 * Date: 10/09/2014
 * Time: 13:59
 */

namespace AVCMS\Core\Form\Type;

use AVCMS\Core\Form\ChoicesProviderInterface;

class SelectType extends DefaultType
{
    public function isValidRequestData($field, $data)
    {
        if (is_array($data) && !isset($field['options']['attr']['multiple'])) {
            return false;
        }
        elseif ($data !== null) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getDefaultOptions($field)
    {
        if (isset($field['options']['choices_provider'])) {
            if (is_object($field['options']['choices_provider']) && $field['options']['choices_provider'] instanceof ChoicesProviderInterface) {
                $field['options']['choices'] = $field['options']['choices_provider']->getChoices();
            }
            elseif (isset($field['options']['choices_provider']['class']) && class_exists($field['options']['choices_provider']['class'])) {
                $choicesProvider = new $field['options']['choices_provider']['class']();
                $field['options']['choices'] = call_user_func(array($choicesProvider, 'getChoices'));
            }
        }

        return $field;
    }
}