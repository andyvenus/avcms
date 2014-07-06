<?php
/**
 * User: Andy
 * Date: 06/02/2014
 * Time: 12:39
 */

namespace AVCMS\Bundles\Blog\Model;

use AVCMS\Core\Model\ContentEntity;
use AVCMS\Core\Validation\Rules\Length;
use AVCMS\Core\Validation\Validator;

class Post extends ContentEntity {
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

    public function setUserId($value) {
        $this->setData('user_id', $value);
    }

    public function getUserId() {
        return $this->data('user_id');
    }

    public function validationRules(Validator $validator)
    {
        $validator->addRule('title', new Length(10), 'The title must be 10 chars long', true);
    }
} 