<?php
/**
 * User: Andy
 * Date: 14/09/2014
 * Time: 14:53
 */

namespace AVCMS\Bundles\CmsFoundation\Form;

use AVCMS\Core\Form\FormBlueprint;

class AdminModuleForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('title', 'text', array(
            'label' => 'Heading / Title',
            'required' => true
        ));
    }
} 