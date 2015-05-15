<?php
/**
 * User: Andy
 * Date: 15/05/15
 * Time: 22:40
 */

namespace AV\Form\Type;

class TextType extends DefaultType
{
    public function processRequestData($field, $data)
    {
        return (isset($field['options']['no_trim']) && $field['options']['no_trim'] == true) ? $data : trim($data);
    }
}
