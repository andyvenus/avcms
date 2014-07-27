<?php
/**
 * User: Andy
 * Date: 26/07/2014
 * Time: 20:47
 */

namespace AVCMS\Bundles\BundleManager\Form;

use AVCMS\Core\Form\FormBlueprint;

class DatabaseSelectForm extends FormBlueprint
{
    public function __construct($tables)
    {
        $this->add('database_table', 'select', array(
            'label' => 'Database Table',
            'choices' => $tables
        ));
    }
}