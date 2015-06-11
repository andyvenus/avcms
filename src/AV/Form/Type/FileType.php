<?php
/**
 * User: Andy
 * Date: 08/10/2014
 * Time: 12:18
 */

namespace AV\Form\Type;

class FileType extends DefaultType
{
    public function getDefaultOptions($field)
    {
        return array_merge(array('allow_unset' => true), $field);
    }

    public function allowUnsetRequest($field)
    {
        return true;
    }

    public function getUnsetRequestData($field)
    {
        return null;
    }

    public function isValidRequestData($field, $data)
    {
        if (isset($field['options']['attr']['multiple']) && is_array($data)) {
            foreach ($data as $file) {
                if ($this->isValidRequestData($field, $file) === false) {
                    return false;
                }
            }

            return true;
        }

        return (is_object($data) || is_file($data));
    }
}
