<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:39
 */

namespace AVCMS\Bundles\Blog\Model;

use AVCMS\Core\Model\Entity;
use AVCMS\Core\Validation\Rules\Length;
use AVCMS\Core\Validation\Validator;

class Post extends Entity {
    public function setTitle($value) {
        $this->setData('title', $value);
    }

    public function getTitle() {
        return $this->data('title');
    }

    public function setBody($value) {
        $this->setData('body', $value);
    }

    public function getBody() {
        return $this->data('body');
    }

    public function setAuthor($value) {
        $this->setData('author', $value);
    }

    public function getAuthor() {
        return $this->data('author');
    }

    public function validationRules(Validator $validator)
    {
        $validator->addRule('title', new Length(10), 'The title must be 10 chars long', true);
    }
} 