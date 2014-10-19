<?php
/**
 * User: Andy
 * Date: 08/10/2014
 * Time: 12:18
 */

namespace AV\Form\Type;

use AV\Form\FormHandler;

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
        return (is_object($data) || is_file($data));
    }

    public function processRequestData($field, $data)
    {
        return $data;
    }
}