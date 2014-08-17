<?php
/**
 * User: Andy
 * Date: 16/08/2014
 * Time: 12:12
 */

namespace AVCMS\BundlesDev\Profiler\Form;

use AVCMS\Core\Form\FormBlueprint;

class ProfilerSearchForm extends FormBlueprint
{
    public function __construct()
    {
        $this->setMethod('GET');

        $this->add('ip', 'text', array('label' => 'IP'));

        $this->add('url', 'text', array('label' => 'URL'));

        $this->add('token', 'text', array('label' => 'Token'));

        $this->add('from', 'text', array('label' => 'From'));

        $this->add('to', 'text', array('label' => 'To'));

        $this->add('limit', 'text', array('label' => 'Limit', 'default' => 10));
    }
} 