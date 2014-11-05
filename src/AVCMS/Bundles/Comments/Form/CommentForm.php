<?php
/**
 * User: Andy
 * Date: 28/10/14
 * Time: 22:25
 */

namespace AVCMS\Bundles\Comments\Form;

use AV\Form\FormBlueprint;
use AV\Validation\Validatable;

class CommentForm extends FormBlueprint
{
    public function __construct()
    {
        $this->setName('new_comment_form');

        $this->add('comment', 'textarea', [
            'label' => 'Add Comment',
            'required' => true,
            'validation' => [
                ['rule' => 'Length', 'arguments' => [null, 5000]]
            ]
        ]);
    }
}