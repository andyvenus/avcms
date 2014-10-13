<?php
/**
 * User: Andy
 * Date: 25/03/2014
 * Time: 14:15
 */

namespace AVCMS\Core\Form\Type;

use AVCMS\Core\Form\FormHandler;

class RadioType extends DefaultType
{
    public function makeView($field, $allFormData, FormHandler $formHandler)
    {
        $field = parent::makeView($field, $allFormData, $formHandler);

        if (is_numeric($field['value'])) {
            $field['value'] = intval($field['value']);
        }

        return $field;
    }
}