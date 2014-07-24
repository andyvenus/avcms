<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 13:30
 */

namespace AVCMS\Bundles\Blog\Form;

use AVCMS\Bundles\ContentBase\Form\AdminContentForm;

class PostForm extends AdminContentForm
{
    public function __construct($item_id, $user_id)
    {
        $this->add('title', 'text', array(
            'label' => 'Title',
            'required' => true
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
            'default' => $user_id
        ));

        $this->setName('blog_post_form');

        parent::__construct($item_id);
    }
}