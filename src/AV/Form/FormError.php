<?php
/**
 * User: Andy
 * Date: 23/02/2014
 * Time: 21:08
 */

namespace AV\Form;


class FormError implements \Serializable
{
    protected $data;

    public function __construct($param, $message, $translate = false, $translationParams = array())
    {
        $this->data['param'] = $param;
        $this->data['message'] = $message;
        $this->data['translate'] = $translate;
        $this->data['translationParams'] = $translationParams;
    }

    public function setParam($param)
    {
        $this->data['param'] = $param;
    }

    public function getParam()
    {
        return $this->data['param'];
    }

    public function setMessage($message)
    {
        $this->data['message'] = $message;
    }

    public function getMessage()
    {
        return $this->data['message'];
    }

    public function setTranslate($translate)
    {
        $this->data['translate'] = $translate;
    }

    public function getTranslate()
    {
        return $this->data['translate'];
    }

    public function setTranslationParams($translation_params)
    {
        $this->data['translationParams'] = $translation_params;
    }

    public function getTranslationParams()
    {
        return $this->data['translationParams'];
    }

    public function serialize()
    {
        return serialize($this->data);
    }

    public function unserialize($serialized)
    {
        $this->data = unserialize($serialized);
    }
}
