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
    public function __construct($item_id)
    {
        $this->add('title', 'text', array(
            'label' => 'Title',
            'required' => true
        ));

        $this->add('body', 'textarea', array(
            'label' => 'Post content'
        ));

        $this->add('user_id', 'text', array(
            'label' => 'Author',
        ));

        $this->add('tags', 'text', array(
            'label' => 'Tags',
            'default' => 'nanan, batman',
        ));

        $this->setName('blog_post_form');

        parent::__construct($item_id);
    }
}