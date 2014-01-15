<?php
/**
 * User: Andy
 * Date: 11/01/2014
 * Time: 11:15
 */

namespace AVCMS\Core\Form\Tests\Fixtures;

use AVCMS\Core\Form\Form;

class BasicForm extends Form
{
    public function __construct()
    {
        $this->add('name', 'text', array(
            'label' => 'Name'
        ));
    }
} 