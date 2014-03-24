<?php
/**
 * User: Andy
 * Date: 24/03/2014
 * Time: 18:43
 */

namespace AVCMS\Core\Form\Fields;

class BaseField
{
    protected $name;

    protected $options;

    public function __construct($name, $options)
    {
        $this->name = $name;
        $this->options = $options;

        $default_options = $this->getDefaultOptions();

        $this->options = array_merge($default_options, $options);
    }

    public function handleRequestData($data) {
        $this->options['value'] = $data;
    }

    public function handleNoRequestData() {

    }

    public function getValue()
    {
        if (isset($this->options['value'])) {
            return $this->options['value'];
        }
        else {
            return null;
        }
    }

    public function allowUnset()
    {
        if (isset($this->options['allow_unset']) && $this->options['allow_unset'] === true) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isRequired()
    {
        if (isset($this->options['required']) && $this->options['required'] === true) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOption($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        else {
            return null;
        }
    }

    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function getDefaultOptions() {
        return array();
    }

    public function getFieldType()
    {
        return 'input';
    }

    public function makeView()
    {
        return $this->options;
    }
} 