<?php
/**
 * User: Andy
 * Date: 26/07/2014
 * Time: 16:56
 */

namespace AVCMS\BundlesDev\BundleManager\Form;

use AV\Form\FormBlueprint;

class NewBundleForm extends FormBlueprint
{
    public function __construct()
    {
        $this->setName('new_bundle');

        $this->add('name', 'text', array(
            'label' => 'Bundle Name',
            'required' => true
        ));

        $this->add('namespace', 'text', array(
            'label' => 'Parent Namespace',
            'default' => 'AVCMS\Bundles',
            'required' => true
        ));

        $this->add('location', 'text', array(
            'label' => 'Parent Directory',
            'default' => 'src/AVCMS/Bundles',
            'required' => true
        ));
    }
} 