<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 13:30
 */

namespace AVCMS\Bundles\Blog\Form;

use AVCMS\Core\Form\FormBlueprint;

class PostForm extends FormBlueprint {
    public function __construct($username)
    {
        $this->add('title', 'text', array(
            'label' => 'Title'
        ));

        $this->add('body', 'textarea', array(
            'label' => 'Post content'
        ));

        $this->add('author', 'select', array(
            'label' => 'Author',
            'default' => $username,
            'choices' => array(
                '1' => 'Andy'
            )
        ));

        $this->add('b[]', 'text', array(
            'label' => 'Title'
        ));

        $this->add('b[]', 'text', array(
            'label' => 'Title'
        ));

        $this->setName('blog_post_form');
    }
} 