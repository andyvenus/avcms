<?php
/**
 * User: Andy
 * Date: 22/04/2014
 * Time: 20:18
 */

namespace AVCMS\Bundles\Blog\Form;

use AVCMS\Core\Form\FormBlueprint;

class BlogPostsFilterForm extends FormBlueprint
{
    public function __construct()
    {
        $this->add('order', 'select', array(
            'choices' => array(
                'newest' => 'Newest',
                'oldest' => 'Oldest'
            )
        ));

        $this->add('search', 'text');

        $this->setName('filter_form');
    }
} 