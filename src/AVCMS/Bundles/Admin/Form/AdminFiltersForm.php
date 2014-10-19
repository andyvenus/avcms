<?php
/**
 * User: Andy
 * Date: 28/07/2014
 * Time: 11:09
 */

namespace AVCMS\Bundles\Admin\Form;

use AV\Form\FormBlueprint;

class AdminFiltersForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('order', 'select', array(
            'choices' => array(
                'newest' => 'Newest (by ID)',
                'oldest' => 'Oldest (by ID)',
            )
        ));

        $this->add('search', 'text');

        $this->setName('filter_form');
    }
} 