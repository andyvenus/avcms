<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 13:30
 */

namespace AVCMS\Bundles\Blog\Form;

use AVCMS\Core\Form\FormBlueprint;
use AVCMS\Core\Validation\Rules\Length;
use AVCMS\Core\Validation\Validator;

class PostForm extends FormBlueprint {
    public function __construct($username)
    {
        $this->add('title', 'text', array(
            'label' => 'Title'
        ));

        $this->add('body', 'textarea', array(
            'label' => 'Post content'
        ));

        $this->add('user_id', 'select', array(
            'label' => 'Author',
            'default' => $username,
            'choices' => array(
                '1' => 'Andy'
            )
        ));

        $this->setName('blog_post_form');
    }
}