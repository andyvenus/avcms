<?php

namespace AVCMS\Form;

class FormOutput {

    protected $form_builder;
    protected $fields;
    protected $params;

    public function __construct(FormBuilder $form) {
        $this->form_builder = $form;
        $this->fields = $form->getFields();
        $this->params = $form->getParameters();
    }

    public function printForm($echo = false) {
        $form = '<form method="post">';
        foreach ($this->fields as $field) {
            $function = "format".ucfirst($field['fieldtype']);
            if (method_exists($this, $function)) {
                $form .= $this->$function($field, $this->params[$field['name']]).'<br>';
            }
            else {
                throw new \Exception("Format method not found for '{$field['fieldtype']}'");
            }
        }
        $form .= '</form>';

        return $form;
    }

    public function formatSelect($select, $selected = null)
    {
        $element = '<select name="'.$select['name'].'" {attributes}>';
        $element .= $this->processSelectArray($select['options'], $selected);
        $element .= '</select>';

        return $element;
    }

    public function formatInput($input, $value = null)
    {
        return '<input name="'.$input['name'].'" type="'.$input['type'].'" value="'.$value.'" {attributes}/>';
    }

    public function formatTextarea($textarea, $value = null)
    {
        return '<textarea name="'.$textarea['name'].'" {attributes}>'.$value.'</textarea>';
    }

    public function formatButton($button, $value = null)
    {
        return '<button name="'.$button['name'].'" type="'.$button['type'].'" {attributes}>'.$button['label'].'</button>';
    }

    protected function processSelectArray($values, $selected = false)
    {
        $select_data = '';

        foreach($values as $value => $option) {
            if (is_array($option)) {
                $select_data .= '<optgroup label="'.$value.'">';
                $select_data .= $this->processSelectArray($option, $selected);
                $select_data .= '</optgroup>';
            }
            else {
                if ($selected == $value)
                    $selected_html = ' selected';
                else
                    $selected_html = '';

                $select_data .= '<option value="'.$value.'"'.$selected_html.'>'.$option.'</option>';
            }
        }
        return $select_data;
    }
}