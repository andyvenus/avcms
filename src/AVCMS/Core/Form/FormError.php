<?php
/**
 * User: Andy
 * Date: 23/02/2014
 * Time: 21:08
 */

namespace AVCMS\Core\Form;


class FormError
{
    protected $param;
    protected $message;
    protected $translate;

    public function __construct($param, $message, $translate = false)
    {
        $this->param = $param;
        $this->message = $message;
        $this->translate = $translate;
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
}