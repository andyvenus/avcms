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
                'newest' => 'Newest (by ID)',
                'oldest' => 'Oldest (by ID)',
                'publish_date_newest' => 'Newest (by publish date)',
                'publish_date_oldest' => 'Oldest (by publish date)',
                'a_z' => 'A-Z',
                'z_a' => 'Z-A'
            )
        ));

        $this->add('search', 'text');

        $this->setName('filter_form');
    }
} 