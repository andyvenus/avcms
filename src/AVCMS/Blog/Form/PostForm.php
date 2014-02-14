<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 13:30
 */

namespace AVCMS\Blog\Form;

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

        $this->add('author', 'text', array(
            'label' => 'Author',
            'default' => $username
        ));

        $this->setName('blog_post_form');
    }
} 