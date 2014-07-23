<?php
/**
 * User: Andy
 * Date: 11/01/2014
 * Time: 11:15
 */

namespace AVCMS\Core\Form\Tests\Fixtures;

use AVCMS\Core\Form\FormBlueprint;

class BasicForm extends FormBlueprint
{
    public function __construct()
    {
        $this->setName('basic_form');

        $this->add('name', 'text', array(
            'label' => 'Name',
            'required' => true
        ));
    }
} 