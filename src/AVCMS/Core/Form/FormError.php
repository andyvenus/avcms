<?php
/**
 * User: Andy
 * Date: 23/02/2014
 * Time: 21:08
 */

namespace AVCMS\Core\Form;


class FormError
{
    public $param;
    public $message;
    protected $translate;
    protected $translation_params;

    public function __construct($param, $message, $translate = false, $translation_params = array())
    {
        $this->param = $param;
        $this->message = $message;
        $this->translate = $translate;
        $this->translation_params = $translation_params;
    }

    public function setParam($param)
    {
        $this->param = $param;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setTranslate($translate)
    {
        $this->translate = $translate;
    }

    public function getTranslate()
    {
        return $this->translate;
    }

    public function setTranslationParams($translation_params)
    {
        $this->translation_params = $translation_params;
    }

    public function getTranslationParams()
    {
        return $this->translation_params;
    }
}