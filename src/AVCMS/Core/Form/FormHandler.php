<?php
/**
 * User: Andy
 * Date: 12/01/2014
 * Time: 11:02
 */

namespace AVCMS\Core\Form;


class FormHandler
{
    protected $form;

    protected $data;

    protected $fields = array();

    protected $submitted = false;

    public function __construct(Form $form)
    {
        $this->form = $form;

        $this->fields = $form->getFields();
        $this->data = $form->getDefaultData();
    }

    public function getData($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        else {
            return null;
        }
    }

    public function setDefaultValues(array $default_values)
    {
        foreach ($default_values as $name => $value) {
            if (in_array($name, $this->fields)) {
                $this->data[$name] = $value;
            }
        }
    }

    /**
     * @param $entity
     * @param null $fields
     * TODO: Make pluggable
     */
    public function addEntity($entity, $fields = null)
    {
        $this->entities[] = array('entity' => $entity, 'fields' => $fields);

        if (method_exists($entity, 'toArray')) {
            $data = $entity->toArray();

            foreach($this->fields as $field) {
                if (isset($data[$field]) && ($fields == null || in_array($field, $fields))) {
                    $this->data[$field] = $data[$field];
                }
            }
        }
        else {
            foreach($this->fields as $field) {
                $getter_name = "get".$this->dashesToCamelCase($field, true);

                if (method_exists($entity, $getter_name) && ($value = $entity->$getter_name()) != null) {
                    $this->data[$field] = $value;
                }
            }
        }
    }

    public function handleRequest($request, $type = 'symfony')
    {
        $this->submitted = true;

        if ($type == 'symfony') {
            foreach ($this->fields as $field) {
                if (!$request->request->has($field)) {
                    $this->submitted = false;
                    break;
                }
                else {
                    $req_data[$field] = $request->request->get($field);
                }
            }
        }
        elseif ($type == 'standard') {
            foreach ($this->fields as $field) {
                if (!isset($request[$field])) {
                    $this->submitted = false;
                    break;
                }
                else {
                    $req_data[$field] = $request[$field];
                }
            }
        }

        if ($this->submitted == true && isset($req_data)) {
            $this->data = $req_data;
        }
        else {
            $this->submitted = false;
        }
    }

    public function isSubmitted()
    {
        return $this->submitted;
    }

    public function getField($name)
    {
        if (!$this->form->has($name)) {
            return false;
        }

        $field = $this->form->get($name);
        $field['value'] = $this->getData($name);

        return $field;
    }

    protected function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
} 