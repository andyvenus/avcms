<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 13:30
 */

namespace AVCMS\Bundles\Blog\Form;

use AV\Validation\Rules\MustNotExist;
use AV\Validation\Validator;
use AVCMS\Bundles\Admin\Form\AdminContentForm;

class BlogPostAdminForm extends AdminContentForm
{
    public function __construct($itemId, $userId)
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
                'data-html-editor' => 1
            )
        ));

        $this->add('tags', 'text', array(
            'label' => 'Tags'
        ));

        $this->add('user_id', 'text', array(
            'label' => 'Credited Author',
            'default' => $userId,
            'attr' => array(
                'class' => 'user_selector no_select2'
            )
        ));

        $this->setName('blog_post_form');

        parent::__construct($itemId);
    }

    public function getValidationRules(Validator $validator)
    {
        $validator->addRule('slug', new MustNotExist('AVCMS\Bundles\Blog\Model\BlogPosts', 'slug', $this->itemId), 'The URL Slug must be unique, slug already in use');
    }
}
