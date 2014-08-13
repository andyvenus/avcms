<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 13:30
 */

namespace AVCMS\Bundles\Blog\Form;

use AVCMS\Bundles\Admin\Form\AdminContentForm;

class PostForm extends AdminContentForm
{
    public function __construct($item_id, $user_id)
    {
        $this->add('title', 'text', array(
            'label' => 'Title',
            'required' => true,
            'attr' => array(
                'data-slug-target' => 'slug'
            )
        ));

        $this->add('body', 'textarea', array(
            'label' => 'Post content',
            'attr' => array(
                'rows' => 10,
            )
        ));

        $this->add('tags', 'text', array(
            'label' => 'Tags'
        ));

        $this->add('user_id', 'text', array(
            'label' => 'Credited Author',
            'default' => $user_id,
            'attr' => array(
                'class' => 'user_selector no_select2'
            )
        ));

        $this->setName('blog_post_form');

        parent::__construct($item_id);
    }
}